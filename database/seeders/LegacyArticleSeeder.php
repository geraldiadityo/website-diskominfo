<?php

namespace Database\Seeders;

use App\Enums\ArticleStatus;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class LegacyArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $sqlPath = database_path('seeders/data/sql_diskominfo_baru2.sql');
        if (!File::exists($sqlPath)) {
            $this->command->error("File SQl tidak Di temukan di: {$sqlPath}");
            return;
        }

        $this->command->info('1. execute raw SQL ke file staging table....');
        DB::unprepared(File::get($sqlPath));

        $this->command->info('2. Memastikan Relasi Data (Author & Kategori) tersedia');

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password')
            ]
        );

        $kategori = Category::firstOrCreate(
            ['slug' => 'berita Umum'],
            ['name' => 'Berita Umum']
        );

        $this->command->info('3. Starting Process Transform and Load...');

        $now = Carbon::now();

        DB::beginTransaction();

        try {
            DB::table('berita')->orderBy('id')->chunk(500, function ($legacyData) use ($now, $admin, $kategori) {
                $newArticles = [];

                foreach ($legacyData as $row) {
                    $fileName = basename($row->gambar);
                    $newPath = 'articles/' . $fileName;
                    $newArticles[] = [
                        'title' => $row->judul,
                        'content' => $row->isi,
                        'featured_image' => $newPath,
                        'slug' => $row->slug ?: Str::slug($row->judul),
                        'author_id' => $admin->id,
                        'category_id' => $kategori->id,
                        'status' => ArticleStatus::PUBLISH->value,
                        'publish_at' => $row->created_at ?: $now,
                        'created_at' => $row->created_at ?: $now,
                        'updated_at' => $row->updated_at ?: $now,
                        'seo_title' => null,
                        'seo_description' => null,
                        'views' => 0
                    ];
                }

                Article::upsert(
                    $newArticles,
                    ['slug'],
                    ['title', 'content', 'featured_image', 'category_id', 'author_id', 'updated_at'],
                );
            });
            DB::commit();
            $this->command->info('✅ Migrasi artikel legacy berhasil di-commit ke database!');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Gagal Melakukan migrasi. rollback database dijalankan');
            $this->command->error($e->getMessage());

            return;
        }

        $this->command->info('4. Membersihkan tabel staging peninggalan SQL lama...');

        $tablesToDrop = [
            'agenda',
            'anggaran',
            'berita',
            'dokumen',
            'foto',
            'informasi',
            'kepuasan',
            'pelayanan',
            'penghargaan',
            'pengumuman',
            'pesan',
            'profil',
            'user'
        ];

        foreach ($tablesToDrop as $table) {
            DB::statement("DROP TABLE IF EXISTS `{$table}`");
        }

        $this->command->info('✅ Proses Seeding & Cleanup selesai 100% tanpa error!');
    }
}
