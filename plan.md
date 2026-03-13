# Plan Arsitektur Teknis — Portal Web Diskominfo

> Dokumen perencanaan arsitektur teknis untuk **Company Profile & Public Portal Website Diskominfo**.
> Disusun berdasarkan analisis UI Design dari Google Stitch (Project ID: `17826962431898095121`).

---

## 1. Executive Summary & Tech Stack Overview

### Ringkasan Arsitektur

Aplikasi ini menggunakan arsitektur **monolitik Laravel** dengan pemisahan tanggung jawab yang jelas:

| Ranah | Teknologi | Tanggung Jawab |
|-------|-----------|----------------|
| **Frontend (Publik)** | Livewire 3 + Tailwind CSS v4 | Halaman yang diakses masyarakat umum (Beranda, Berita, Publikasi, Profil, Kontak) |
| **Backend (Admin)** | Laravel Filament 4 | Dasbor CMS untuk manajemen konten (CRUD Berita, Publikasi, Galeri, Organisasi, Kontak) |
| **Database** | MySQL 8.x | Penyimpanan data relasional |
| **RBAC** | Spatie Permission 6.x | Manajemen role & permission terintegrasi Filament |

```
┌─────────────────────────────────────────────────────┐
│                   Laravel 12 (Monolith)              │
│                                                      │
│  ┌──────────────────┐    ┌────────────────────────┐  │
│  │   PUBLIC PORTAL   │    │    ADMIN DASHBOARD     │  │
│  │   (Livewire 3)    │    │    (Filament 4)        │  │
│  │                    │    │                        │  │
│  │  • Homepage        │    │  • NewsResource        │  │
│  │  • Berita List     │    │  • PublicationResource │  │
│  │  • Berita Detail   │    │  • GalleryResource     │  │
│  │  • Publikasi       │    │  • OrganizationResource│  │
│  │  • Struktur Org    │    │  • ContactResource     │  │
│  │  • Kontak          │    │  • CategoryResource    │  │
│  │                    │    │  • TagResource         │  │
│  └────────┬───────────┘    └──────────┬─────────────┘  │
│           │                           │                │
│           └──────────┬────────────────┘                │
│                      ▼                                 │
│            ┌──────────────────┐                        │
│            │   Eloquent ORM   │                        │
│            │   + Cache Layer  │                        │
│            └────────┬─────────┘                        │
│                     ▼                                  │
│            ┌──────────────────┐                        │
│            │     MySQL 8.x    │                        │
│            └──────────────────┘                        │
└─────────────────────────────────────────────────────┘
```

### Tech Stack

| Komponen | Versi | Keterangan |
|----------|-------|------------|
| PHP | 8.4 | Runtime utama |
| Laravel | 12 | Framework backend |
| Livewire | 3 | Komponen frontend reaktif |
| Filament | 4 | Admin panel & CMS |
| Tailwind CSS | 4 | Utility-first CSS framework |
| MySQL | 8.x | Database relasional |
| Spatie Permission | 6.x | RBAC package |
| Spatie Media Library | 11.x | Manajemen file/gambar |
| Laravel Pint | 1.x | Code formatter |
| Pest | 4.x | Testing framework |

---

## 2. UI/UX to Component Mapping (Berdasarkan Desain Google Stitch)

### 2.1 Shared Layout Components

Komponen yang digunakan di seluruh halaman publik:

| Blade Component | File Path | Deskripsi |
|-----------------|-----------|-----------|
| `<x-layouts.public>` | `resources/views/components/layouts/public.blade.php` | Layout utama dengan Navbar & Footer |
| `<x-navbar>` | `resources/views/components/navbar.blade.php` | Navigasi: Beranda, Profil, Publikasi, Berita + Search icon |
| `<x-footer>` | `resources/views/components/footer.blade.php` | Footer: alamat kantor, peta situs, link terkait, sosial media, copyright |
| `<x-breadcrumb>` | `resources/views/components/breadcrumb.blade.php` | Breadcrumb navigasi untuk halaman dalam |

### 2.2 Halaman & Komponen Livewire

#### Screen 1: Homepage (`/`)

**Route:** `GET /` → `App\Livewire\Pages\HomePage`

| Seksi UI (dari Stitch) | Livewire Component | Deskripsi |
|-------------------------|--------------------|-----------|
| Hero Banner | `HeroSection` (Blade partial) | Judul "Mewujudkan Transformasi Digital Daerah", subtitle, background, widget cuaca, dan quick-link cards (Layanan Publik, Portal E-Gov, Pengumuman) |
| Profil Pimpinan | `LeaderProfile` (Blade partial) | Foto, nama, jabatan Kepala Dinas, dan kutipan/visi |
| Berita Terbaru | `LatestNews` (Livewire) | 3 card berita terbaru + link "Lihat Semua" → `/berita` |
| Publikasi & Dokumen | `LatestPublications` (Blade partial) | 4 card publikasi (nama, tipe file, ukuran) |

#### Screen 2: Berita Terbaru (`/berita`)

**Route:** `GET /berita` → `App\Livewire\Pages\NewsIndex`

| Seksi UI | Livewire Component | Deskripsi |
|----------|--------------------|-----------|
| Page Header | Inline | Judul "Berita Terbaru" + subtitle |
| Featured Article | Inline | Card besar untuk artikel utama/featured |
| News Grid | `NewsIndex` (full-page Livewire) | Grid card berita (gambar, kategori badge, judul, excerpt, tanggal). Fitur: filter kategori, search `wire:model.live`, pagination `wire:click` |

#### Screen 3: Detail Berita (`/berita/{slug}`)

**Route:** `GET /berita/{news:slug}` → `App\Livewire\Pages\NewsDetail`

| Seksi UI | Livewire Component | Deskripsi |
|----------|--------------------|-----------|
| Article Header | Inline | Judul, author info (nama + avatar), tanggal, estimasi waktu baca |
| Featured Image | Inline | Gambar utama artikel (16:9 aspect ratio) |
| Article Body | Inline | Konten WYSIWYG (prose styling) |
| Sidebar: Berita Terkait | `RelatedNews` (Blade partial) | 3 card berita terkait (berdasarkan kategori) |
| Sidebar: Tag Populer | `PopularTags` (Blade partial) | Tag cloud: #LiterasiDigital, #Diskominfo, dll |
| Newsletter | `NewsletterSubscription` (Livewire) | Form email subscription |

#### Screen 4: Publikasi & Dokumen (`/publikasi`)

**Route:** `GET /publikasi` → `App\Livewire\Pages\PublicationIndex`

| Seksi UI | Livewire Component | Deskripsi |
|----------|--------------------|-----------|
| Page Header | Inline | Judul "Publikasi & Dokumen" + subtitle |
| Document List | `PublicationIndex` (full-page Livewire) | List/grid dokumen: judul, deskripsi, tipe file (PDF/DOC), ukuran, tombol unduh. Fitur: filter kategori, search, pagination |

#### Screen 5: Struktur Organisasi (`/profil/struktur-organisasi`)

**Route:** `GET /profil/struktur-organisasi` → `App\Livewire\Pages\OrganizationStructure`

| Seksi UI | Livewire Component | Deskripsi |
|----------|--------------------|-----------|
| Page Header | Inline | Judul "Struktur Organisasi" + subtitle |
| Leader Card | Inline | Card besar Kepala Dinas (foto, nama, jabatan, visi) |
| Hierarchy Chart | Inline | Bagan organisasi: Kepala Dinas → Sekretariat → 4 Bidang (Pengelolaan Informasi, Infrastruktur TI, Persandian & Statistik, E-Government) |

#### Screen 6 & 7: Hubungi Kami (`/kontak`)

**Route:** `GET /kontak` → `App\Livewire\Pages\ContactPage`

| Seksi UI | Livewire Component | Deskripsi |
|----------|--------------------|-----------|
| Page Header | Inline | Judul "Hubungi Kami" + subtitle |
| Contact Form | `ContactForm` (Livewire) | Form: Nama, Email, Subjek, Pesan + validasi + rate limiting |
| Contact Info | Blade partial | Alamat, Telepon/Fax, Email, Media Sosial |
| FAQ Accordion | `FaqSection` (Blade partial, Alpine.js) | Daftar pertanyaan populer dengan expand/collapse |

### 2.3 Filament Resources (Backend CMS)

| Filament Resource | Model | Fitur Utama |
|-------------------|-------|-------------|
| `NewsResource` | `News` | CRUD berita, WYSIWYG editor, upload gambar, kategori, tag, status (Draft/Published/Archived), scheduled publication |
| `PublicationResource` | `Publication` | CRUD publikasi, upload file dokumen, kategori, tipe file |
| `GalleryResource` | `Gallery` | CRUD galeri foto/video, multiple upload |
| `OrganizationMemberResource` | `OrganizationMember` | CRUD anggota organisasi, jabatan, hierarki, urutan tampil |
| `ContactMessageResource` | `ContactMessage` | View & manage pesan masuk (read-only create, mark as read/responded) |
| `CategoryResource` | `Category` | CRUD kategori untuk berita & publikasi |
| `TagResource` | `Tag` | CRUD tag untuk berita |
| `FaqResource` | `Faq` | CRUD FAQ untuk halaman kontak |
| `SiteSettingResource` | `SiteSetting` | Pengaturan situs: nama, alamat, telepon, email, sosmed, dll |

---

## 3. Database Schema & Relational Mapping

### 3.1 Entity Relationship Diagram

```
┌──────────┐     ┌──────────────┐     ┌───────────┐
│  users   │────<│    news      │>────│categories │
│          │     │              │     │           │
│ id       │     │ id           │     │ id        │
│ name     │     │ user_id (FK) │     │ name      │
│ email    │     │ category_id  │     │ slug      │
│ password │     │ title        │     │ type      │
│          │     │ slug         │     └───────────┘
└──────┬───┘     │ excerpt      │
       │         │ body         │     ┌───────────┐
       │         │ status       │>────│   tags    │
       │         │ published_at │     │           │
       │         │ ...          │     │ id        │
       │         └──────────────┘     │ name      │
       │                              │ slug      │
       │         ┌──────────────┐     └───────────┘
       │────────<│ publications │
       │         │              │     ┌───────────────────┐
       │         │ id           │     │ news_tag (pivot)   │
       │         │ user_id (FK) │     │                    │
       │         │ category_id  │     │ news_id            │
       │         │ title        │     │ tag_id             │
       │         │ ...          │     └───────────────────┘
       │         └──────────────┘
       │
       │         ┌─────────────────────┐
       │         │ model_has_roles     │  (Spatie)
       │         │ model_has_permissions│
       │         │ role_has_permissions │
       │         └─────────────────────┘
```

### 3.2 Tabel-Tabel Utama

#### `users`
```sql
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->string('avatar')->nullable();
    $table->rememberToken();
    $table->timestamps();
});
```

#### `categories`
```sql
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->string('type')->default('news'); -- 'news' | 'publication'
    $table->text('description')->nullable();
    $table->integer('sort_order')->default(0);
    $table->timestamps();

    $table->index(['type', 'sort_order']);
});
```

#### `news`
```sql
Schema::create('news', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
    $table->string('title');
    $table->string('slug')->unique();
    $table->text('excerpt')->nullable();
    $table->longText('body');
    $table->string('featured_image')->nullable();
    $table->string('status')->default('draft'); -- draft | published | archived
    $table->timestamp('published_at')->nullable();
    $table->boolean('is_featured')->default(false);
    $table->unsignedInteger('views_count')->default(0);
    $table->unsignedInteger('version')->default(1); -- optimistic locking
    $table->timestamps();

    $table->index(['status', 'published_at']);
    $table->index('is_featured');
});
```

#### `tags`
```sql
Schema::create('tags', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->timestamps();
});
```

#### `news_tag` (pivot)
```sql
Schema::create('news_tag', function (Blueprint $table) {
    $table->foreignId('news_id')->constrained()->cascadeOnDelete();
    $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
    $table->primary(['news_id', 'tag_id']);
});
```

#### `publications`
```sql
Schema::create('publications', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
    $table->string('title');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->string('file_path');
    $table->string('file_type'); -- pdf, doc, xls
    $table->unsignedBigInteger('file_size'); -- bytes
    $table->string('status')->default('draft'); -- draft | published | archived
    $table->timestamp('published_at')->nullable();
    $table->unsignedInteger('download_count')->default(0);
    $table->timestamps();

    $table->index(['status', 'published_at']);
});
```

#### `galleries`
```sql
Schema::create('galleries', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->boolean('is_published')->default(false);
    $table->integer('sort_order')->default(0);
    $table->timestamps();
});
```

#### `gallery_items`
```sql
Schema::create('gallery_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('gallery_id')->constrained()->cascadeOnDelete();
    $table->string('file_path');
    $table->string('type')->default('image'); -- image | video
    $table->string('caption')->nullable();
    $table->integer('sort_order')->default(0);
    $table->timestamps();
});
```

#### `organization_members`
```sql
Schema::create('organization_members', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('position'); -- Kepala Dinas, Sekretaris, Kepala Bidang
    $table->string('department')->nullable(); -- Bidang/Unit
    $table->text('bio')->nullable();
    $table->string('photo')->nullable();
    $table->foreignId('parent_id')->nullable()->constrained('organization_members')->nullOnDelete();
    $table->integer('sort_order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();

    $table->index(['parent_id', 'sort_order']);
});
```

#### `contact_messages`
```sql
Schema::create('contact_messages', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email');
    $table->string('subject');
    $table->text('message');
    $table->string('status')->default('unread'); -- unread | read | responded
    $table->text('admin_notes')->nullable();
    $table->timestamps();

    $table->index('status');
});
```

#### `faqs`
```sql
Schema::create('faqs', function (Blueprint $table) {
    $table->id();
    $table->string('question');
    $table->text('answer');
    $table->integer('sort_order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();

    $table->index(['is_active', 'sort_order']);
});
```

#### `site_settings`
```sql
Schema::create('site_settings', function (Blueprint $table) {
    $table->id();
    $table->string('key')->unique();
    $table->text('value')->nullable();
    $table->string('group')->default('general');
    $table->timestamps();

    $table->index('group');
});
```

### 3.3 Spatie Permission Tables

Tabel yang di-generate otomatis oleh `php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"`:

- `roles` — Daftar role (Admin, Publisher, Author)
- `permissions` — Daftar permission
- `model_has_roles` — Pivot user ↔ role
- `model_has_permissions` — Pivot user ↔ permission (direct)
- `role_has_permissions` — Pivot role ↔ permission

---

## 4. Role-Based Access Control (RBAC) Strategy

### 4.1 Definisi Role & Permission

| Role | Scope | Deskripsi |
|------|-------|-----------|
| **Admin** | Full Access | Akses penuh ke seluruh fitur CMS termasuk manajemen user, pengaturan situs, dan seluruh konten |
| **Publisher** | News + Publication | Bisa membuat, mengedit, **publish/unpublish**, dan menghapus berita & publikasi milik siapapun |
| **Author** | News + Publication (own) | Hanya bisa membuat (draft) dan mengedit berita & publikasi **miliknya sendiri** |

### 4.2 Permission Matrix

| Permission | Admin | Publisher | Author |
|------------|:-----:|:---------:|:------:|
| `news.view_any` | ✅ | ✅ | ✅ |
| `news.view` | ✅ | ✅ | ✅ (own) |
| `news.create` | ✅ | ✅ | ✅ |
| `news.update` | ✅ | ✅ | ✅ (own) |
| `news.delete` | ✅ | ✅ | ❌ |
| `news.publish` | ✅ | ✅ | ❌ |
| `publication.view_any` | ✅ | ✅ | ✅ |
| `publication.view` | ✅ | ✅ | ✅ (own) |
| `publication.create` | ✅ | ✅ | ✅ |
| `publication.update` | ✅ | ✅ | ✅ (own) |
| `publication.delete` | ✅ | ✅ | ❌ |
| `publication.publish` | ✅ | ✅ | ❌ |
| `gallery.*` | ✅ | ❌ | ❌ |
| `organization.*` | ✅ | ❌ | ❌ |
| `faq.*` | ✅ | ❌ | ❌ |
| `setting.*` | ✅ | ❌ | ❌ |
| `user.*` | ✅ | ❌ | ❌ |
| `contact_message.*` | ✅ | ❌ | ❌ |

### 4.3 Policy Implementation

```php
// app/Policies/NewsPolicy.php
class NewsPolicy
{
    public function update(User $user, News $news): bool
    {
        if ($user->hasRole('Admin') || $user->hasRole('Publisher')) {
            return true;
        }

        // Author hanya bisa edit miliknya sendiri
        return $user->id === $news->user_id;
    }

    public function publish(User $user, News $news): bool
    {
        return $user->hasAnyRole(['Admin', 'Publisher']);
    }

    public function delete(User $user, News $news): bool
    {
        return $user->hasAnyRole(['Admin', 'Publisher']);
    }
}
```

### 4.4 Alur Kerja Konten

```
Author membuat draft
       │
       ▼
  [Status: DRAFT]
       │
       ▼
Publisher/Admin review
       │
       ├── Approve → [Status: PUBLISHED] + set published_at
       │
       └── Reject → kembali ke Author (tetap DRAFT)
       │
       ▼
Publisher/Admin bisa unpublish
       │
       ▼
  [Status: ARCHIVED]
```

### 4.5 Filament Integration

Integrasi RBAC ke Filament menggunakan `Filament Shield` atau manual policy check:

```php
// app/Filament/Resources/NewsResource.php
class NewsResource extends Resource
{
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Author hanya melihat artikelnya sendiri
        if (auth()->user()->hasRole('Author')) {
            $query->where('user_id', auth()->id());
        }

        return $query;
    }
}
```

---

## 5. Security & Data Integrity

### 5.1 XSS Prevention

**Risiko utama:** Input WYSIWYG (body berita/publikasi) mengandung HTML mentah.

| Strategi | Implementasi |
|----------|-------------|
| **Sanitasi Input** | Gunakan `HTMLPurifier` via package `mews/purifier` untuk membersihkan HTML dari editor WYSIWYG sebelum disimpan ke database |
| **Whitelist Tag** | Izinkan hanya tag aman: `<p>`, `<h2-h6>`, `<strong>`, `<em>`, `<ul>`, `<ol>`, `<li>`, `<a>`, `<img>`, `<blockquote>`, `<table>` |
| **Output Escaping** | Untuk field non-HTML, gunakan `{{ }}` (auto-escape). Untuk body artikel, gunakan `{!! $news->body !!}` hanya setelah sanitasi |
| **CSP Headers** | Terapkan Content Security Policy untuk mencegah inline script injection |

```php
// app/Casts/SanitizedHtml.php
class SanitizedHtml implements CastsAttributes
{
    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        return Purifier::clean($value, 'article');
    }
}
```

### 5.2 SQL Injection Prevention

| Strategi | Implementasi |
|----------|-------------|
| **Eloquent ORM** | Selalu gunakan Eloquent query builder, hindari raw query |
| **Parameter Binding** | Jika harus raw query, selalu gunakan parameter binding `DB::select('...?', [$param])` |
| **Validation** | Validasi semua input di Form Request sebelum menyentuh database |

### 5.3 Mass Assignment Protection

```php
// Setiap model WAJIB mendefinisikan $fillable
class News extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'body',
        'featured_image',
        'category_id',
        'status',
        'published_at',
        'is_featured',
    ];
    // JANGAN pernah: protected $guarded = [];
}
```

### 5.4 Rate Limiting

```php
// bootstrap/app.php
->withMiddleware(function (Middleware $middleware) {
    $middleware->throttleApi('60,1'); // API: 60 requests/minute
})

// routes/web.php — Rate limit spesifik
Route::post('/kontak', ContactForm::class)
    ->middleware('throttle:5,1'); // 5 submit per menit

Route::get('/berita', NewsIndex::class)
    ->middleware('throttle:30,1'); // 30 requests per menit untuk search
```

### 5.5 File Upload Security

```php
// Validasi file upload
$rules = [
    'featured_image' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
    'document'       => ['file', 'mimes:pdf,doc,docx,xls,xlsx', 'max:20480'],
];
```

---

## 6. Performance & Potential Issues (CRITICAL)

### 6.1 N+1 Query Problem

#### Problem Area: Halaman Berita (`NewsIndex`)

Menampilkan daftar berita beserta **kategori** dan **penulis** → tanpa eager loading akan menghasilkan N+1 query.

```php
// ❌ BURUK — N+1 Problem
$news = News::where('status', 'published')->get();
// Setiap iterasi: SELECT * FROM categories WHERE id = ?
// Setiap iterasi: SELECT * FROM users WHERE id = ?

// ✅ BENAR — Eager Loading
$news = News::query()
    ->with(['category:id,name,slug', 'author:id,name,avatar'])
    ->where('status', 'published')
    ->orderByDesc('published_at')
    ->paginate(9);
```

#### Problem Area: Detail Berita (`NewsDetail`)

```php
// ✅ Eager load relasi yang dibutuhkan
$news = News::query()
    ->with([
        'category:id,name,slug',
        'author:id,name,avatar',
        'tags:id,name,slug',
    ])
    ->where('slug', $slug)
    ->where('status', 'published')
    ->firstOrFail();

// Berita terkait — query terpisah, bukan N+1
$relatedNews = News::query()
    ->with(['category:id,name,slug'])
    ->where('category_id', $news->category_id)
    ->where('id', '!=', $news->id)
    ->where('status', 'published')
    ->latest('published_at')
    ->limit(3)
    ->get();
```

#### Problem Area: Homepage

```php
// ✅ Semua data di-load dengan eager loading
$latestNews = News::query()
    ->with(['category:id,name,slug', 'author:id,name'])
    ->where('status', 'published')
    ->latest('published_at')
    ->limit(3)
    ->get();

$latestPublications = Publication::query()
    ->with(['category:id,name'])
    ->where('status', 'published')
    ->latest('published_at')
    ->limit(4)
    ->get();
```

#### Debugging: Preventive Measures

```php
// app/Providers/AppServiceProvider.php
public function boot(): void
{
    // Di environment non-production, tangkap lazy loading
    Model::preventLazyLoading(! app()->isProduction());
}
```

### 6.2 Race Condition: Concurrent Publish/Unpublish

**Skenario:** Dua Publisher secara bersamaan mengubah status publish sebuah artikel.

#### Solusi: Optimistic Locking

```php
// Model News sudah memiliki kolom `version`

// app/Actions/PublishNewsAction.php
class PublishNewsAction
{
    public function execute(News $news): bool
    {
        $affected = News::query()
            ->where('id', $news->id)
            ->where('version', $news->version) // Check versi saat ini
            ->update([
                'status' => 'published',
                'published_at' => now(),
                'version' => $news->version + 1,
            ]);

        if ($affected === 0) {
            // Versi sudah berubah — ada orang lain yang mengubah
            throw new StaleModelException(
                'Artikel ini telah diubah oleh pengguna lain. Silakan muat ulang halaman.'
            );
        }

        return true;
    }
}
```

#### Filament Integration

```php
// Pada Filament Resource, tampilkan notifikasi jika terjadi conflict
// Dengan menangkap StaleModelException di action handler
```

### 6.3 Race Condition: Download Counter

**Skenario:** Banyak user men-download file publikasi secara bersamaan.

```php
// ✅ BENAR — Atomic increment, tidak baca-lalu-tulis
Publication::query()
    ->where('id', $publication->id)
    ->increment('download_count');

// ❌ BURUK — Race condition
$pub = Publication::find($id);
$pub->download_count = $pub->download_count + 1;
$pub->save();
```

### 6.4 Caching Strategy

Karena portal pemerintah bersifat **read-heavy** dengan frekuensi update konten yang rendah, caching sangat efektif.

| Data | Cache Driver | TTL | Invalidation |
|------|-------------|-----|-------------|
| Homepage (latest news + publications) | File | 10 menit | Event-driven saat konten baru di-publish |
| Daftar Kategori | File | 60 menit | Saat kategori di-CRUD |
| Struktur Organisasi | File | 24 jam | Saat data organisasi diubah |
| FAQ | File | 24 jam | Saat FAQ di-CRUD |
| Site Settings | File | 24 jam | Saat settings diubah |
| Single News Article | File | 30 menit | Saat artikel di-update |

#### Implementasi

```php
// Contoh caching di Homepage
class HomePage extends Component
{
    public function render(): View
    {
        return view('livewire.pages.home-page', [
            'latestNews' => Cache::remember('homepage.latest_news', 600, function () {
                return News::query()
                    ->with(['category:id,name,slug', 'author:id,name'])
                    ->where('status', 'published')
                    ->latest('published_at')
                    ->limit(3)
                    ->get();
            }),
            'latestPublications' => Cache::remember('homepage.latest_publications', 600, function () {
                return Publication::query()
                    ->with(['category:id,name'])
                    ->where('status', 'published')
                    ->latest('published_at')
                    ->limit(4)
                    ->get();
            }),
        ]);
    }
}
```

#### Cache Invalidation (Event-Driven)

```php
// app/Observers/NewsObserver.php
class NewsObserver
{
    public function saved(News $news): void
    {
        Cache::forget('homepage.latest_news');
        Cache::forget("news.detail.{$news->slug}");
    }

    public function deleted(News $news): void
    {
        Cache::forget('homepage.latest_news');
        Cache::forget("news.detail.{$news->slug}");
    }
}
```

#### Cache Driver

Untuk VPS dengan aaPanel, gunakan **File cache** (default Laravel) karena:
- Tidak perlu instalasi tambahan (Redis/Memcached)
- Cukup performant untuk skala portal pemerintah kota
- Mudah di-manage (clear cache = hapus folder)

Jika traffic tinggi di kemudian hari, bisa upgrade ke Redis tanpa perubahan kode (hanya ubah `CACHE_DRIVER=redis` di `.env`).

---

## 7. Deployment & Infrastructure (aaPanel Context)

### 7.1 Server Requirements

| Requirement | Minimum | Recommended |
|-------------|---------|-------------|
| **OS** | Ubuntu 20.04 LTS | Ubuntu 22.04 LTS |
| **PHP** | 8.4+ | 8.4+ |
| **MySQL** | 8.0+ | 8.0+ |
| **RAM** | 2 GB | 4 GB |
| **Storage** | 20 GB SSD | 40 GB SSD |
| **Web Server** | Nginx (via aaPanel) | Nginx (via aaPanel) |

### 7.2 PHP Extensions (WAJIB)

```
✅ BCMath        — Filament dependency
✅ Ctype         — Laravel core
✅ cURL          — HTTP client
✅ DOM           — HTMLPurifier
✅ Fileinfo      — File upload validation
✅ GD / Imagick  — Image processing
✅ Intl          — Internationalization (Filament)
✅ JSON          — Laravel core
✅ Mbstring      — Laravel core
✅ OpenSSL       — Encryption
✅ PDO MySQL     — Database driver
✅ Tokenizer     — Laravel core
✅ XML           — Laravel core
✅ Zip           — Composer dependency
```

### 7.3 aaPanel Configuration Checklist

#### Nginx Virtual Host

```nginx
server {
    listen 80;
    server_name diskominfo.example.go.id;
    root /www/wwwroot/diskominfo-website/public;
    index index.php;

    # Gzip compression
    gzip on;
    gzip_types text/css application/javascript application/json image/svg+xml;

    # Static file caching
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|woff2)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/tmp/php-cgi-84.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

#### Storage Symlink

```bash
cd /www/wwwroot/diskominfo-website
php artisan storage:link
```

#### Production Optimization

```bash
# Cache configuration, routes, dan views
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan icons:cache    # Filament icons
php artisan filament:cache-components

# Build frontend assets
npm run build
```

### 7.4 Scheduled Tasks (Cron)

```bash
# aaPanel → Cron Jobs → Tambahkan:
* * * * * cd /www/wwwroot/diskominfo-website && php artisan schedule:run >> /dev/null 2>&1
```

**Scheduled tasks yang diperlukan:**

```php
// routes/console.php
Schedule::command('news:publish-scheduled')
    ->everyMinute()
    ->description('Publish artikel yang dijadwalkan');

Schedule::command('cache:prune-stale-tags')
    ->hourly()
    ->description('Bersihkan cache tag yang expired');
```

### 7.5 Queue Worker (Opsional, via Supervisor)

Jika menggunakan queue untuk email notification atau heavy processing:

```ini
# /etc/supervisor/conf.d/diskominfo-worker.conf
[program:diskominfo-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /www/wwwroot/diskominfo-website/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www
numprocs=2
redirect_stderr=true
stdout_logfile=/www/wwwroot/diskominfo-website/storage/logs/worker.log
stopwaitsecs=3600
```

### 7.6 Environment Variables Checklist (`.env`)

```env
APP_NAME="Diskominfo"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://diskominfo.example.go.id

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=diskominfo
DB_USERNAME=diskominfo_user
DB_PASSWORD=<strong-password>

CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database  # atau sync jika tidak pakai queue

MAIL_MAILER=smtp
MAIL_HOST=smtp.example.go.id
MAIL_PORT=587
MAIL_USERNAME=noreply@diskominfo.go.id
MAIL_PASSWORD=<mail-password>
MAIL_ENCRYPTION=tls

FILAMENT_PATH=admin
```

### 7.7 Post-Deployment Steps

```bash
# 1. Migrasi database
php artisan migrate --force

# 2. Seed data awal (roles, permissions, admin user)
php artisan db:seed --class=RolePermissionSeeder
php artisan db:seed --class=AdminUserSeeder

# 3. Storage symlink
php artisan storage:link

# 4. Optimasi
php artisan optimize
php artisan filament:cache-components
php artisan icons:cache

# 5. Build assets
npm install --production
npm run build

# 6. Set permission folder
chown -R www:www storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

---

## Lampiran: File & Folder Structure

```
app/
├── Actions/
│   └── PublishNewsAction.php
├── Casts/
│   └── SanitizedHtml.php
├── Filament/
│   └── Resources/
│       ├── NewsResource.php
│       ├── PublicationResource.php
│       ├── GalleryResource.php
│       ├── OrganizationMemberResource.php
│       ├── ContactMessageResource.php
│       ├── CategoryResource.php
│       ├── TagResource.php
│       ├── FaqResource.php
│       └── SiteSettingResource.php
├── Http/
│   └── Controllers/
│       └── PublicationDownloadController.php
├── Livewire/
│   ├── Pages/
│   │   ├── HomePage.php
│   │   ├── NewsIndex.php
│   │   ├── NewsDetail.php
│   │   ├── PublicationIndex.php
│   │   ├── OrganizationStructure.php
│   │   └── ContactPage.php
│   └── Components/
│       ├── LatestNews.php
│       ├── ContactForm.php
│       └── NewsletterSubscription.php
├── Models/
│   ├── User.php
│   ├── News.php
│   ├── Category.php
│   ├── Tag.php
│   ├── Publication.php
│   ├── Gallery.php
│   ├── GalleryItem.php
│   ├── OrganizationMember.php
│   ├── ContactMessage.php
│   ├── Faq.php
│   └── SiteSetting.php
├── Observers/
│   ├── NewsObserver.php
│   └── PublicationObserver.php
├── Policies/
│   ├── NewsPolicy.php
│   └── PublicationPolicy.php
└── Providers/
    └── AppServiceProvider.php

resources/views/
├── components/
│   ├── layouts/
│   │   └── public.blade.php
│   ├── navbar.blade.php
│   ├── footer.blade.php
│   └── breadcrumb.blade.php
└── livewire/
    └── pages/
        ├── home-page.blade.php
        ├── news-index.blade.php
        ├── news-detail.blade.php
        ├── publication-index.blade.php
        ├── organization-structure.blade.php
        └── contact-page.blade.php

database/
├── migrations/
│   ├── xxxx_create_categories_table.php
│   ├── xxxx_create_news_table.php
│   ├── xxxx_create_tags_table.php
│   ├── xxxx_create_news_tag_table.php
│   ├── xxxx_create_publications_table.php
│   ├── xxxx_create_galleries_table.php
│   ├── xxxx_create_gallery_items_table.php
│   ├── xxxx_create_organization_members_table.php
│   ├── xxxx_create_contact_messages_table.php
│   ├── xxxx_create_faqs_table.php
│   └── xxxx_create_site_settings_table.php
└── seeders/
    ├── RolePermissionSeeder.php
    └── AdminUserSeeder.php
```
