# Plan Arsitektur Teknis — Portal Web Diskominfo

> Dokumen perencanaan arsitektur teknis untuk **Company Profile & Public Portal Website Diskominfo**.
> Disusun berdasarkan analisis UI Design dari Google Stitch (Project ID: `17826962431898095121`).
> **Dioptimasi berdasarkan implementasi backend Laravel Filament yang sudah berjalan.**

---

## 1. Executive Summary & Tech Stack Overview

### Ringkasan Arsitektur

Aplikasi ini menggunakan arsitektur **monolitik Laravel** dengan pemisahan tanggung jawab yang jelas:

| Ranah | Teknologi | Tanggung Jawab |
|-------|-----------|----------------|
| **Frontend (Publik)** | Livewire 3 + Tailwind CSS v4 | Halaman yang diakses masyarakat umum (Beranda, Berita, Publikasi, Profil, Kontak) |
| **Backend (Admin)** | Laravel Filament 4 | Dasbor CMS untuk manajemen konten (CRUD Berita, Publikasi, Organisasi, Kontak) |
| **Database** | SQLite (dev) / MySQL 8.x (prod) | Penyimpanan data relasional |
| **RBAC** | Simple role column pada `users` | Role-based access via kolom `role` (admin, publisher, author) |

```
┌─────────────────────────────────────────────────────┐
│                   Laravel 12 (Monolith)              │
│                                                      │
│  ┌──────────────────┐    ┌────────────────────────┐  │
│  │   PUBLIC PORTAL   │    │    ADMIN DASHBOARD     │  │
│  │   (Livewire 3)    │    │    (Filament 4)        │  │
│  │                    │    │                        │  │
│  │  • Homepage        │    │  • ArticleResource     │  │
│  │  • Berita List     │    │  • PublicationResource │  │
│  │  • Berita Detail   │    │  • OrganizationMember  │  │
│  │  • Publikasi       │    │  • CategoryResource    │  │
│  │  • Struktur Org    │    │  • TipeResource        │  │
│  │  • Kontak          │    │  • TagResource (*)     │  │
│  │                    │    │  • DepartementResource │  │
│  │  (*) = belum       │    │  • PositionResource    │  │
│  │  diimplementasi    │    │  • UserResource        │  │
│  │                    │    │  • ManageSettings Page │  │
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
│            │ SQLite/MySQL 8.x │                        │
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
| SQLite / MySQL | 3 / 8.x | Database (dev / prod) |
| Laravel Pint | 1.x | Code formatter |
| Pest | 4.x | Testing framework |

> **Catatan:** Project ini **tidak** menggunakan Spatie Permission. RBAC dikelola melalui kolom `role` pada tabel `users`.

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

> **Status:** Frontend Livewire belum diimplementasi. Saat ini hanya `routes/web.php` dengan welcome view.
> Seluruh halaman di bawah ini merupakan **rencana implementasi**.

#### Screen 1: Homepage (`/`)

**Route:** `GET /` → `App\Livewire\Pages\HomePage`

| Seksi UI (dari Stitch) | Livewire Component | Deskripsi |
|-------------------------|--------------------|-----------| 
| Hero Banner | `HeroSection` (Blade partial) | Judul "Mewujudkan Transformasi Digital Daerah", subtitle, background, widget cuaca, dan quick-link cards (Layanan Publik, Portal E-Gov, Pengumuman) |
| Profil Pimpinan | `LeaderProfile` (Blade partial) | Foto, nama, jabatan Kepala Dinas, dan kutipan/visi |
| Berita Terbaru | `LatestNews` (Livewire) | 3 card berita terbaru dari model `Article` + link "Lihat Semua" → `/berita` |
| Publikasi & Dokumen | `LatestPublications` (Blade partial) | 4 card publikasi (nama, tipe file, ukuran) dari model `Publication` |

#### Screen 2: Berita Terbaru (`/berita`)

**Route:** `GET /berita` → `App\Livewire\Pages\NewsIndex`

| Seksi UI | Livewire Component | Deskripsi |
|----------|--------------------|-----------| 
| Page Header | Inline | Judul "Berita Terbaru" + subtitle |
| Featured Article | Inline | Card besar untuk artikel utama/featured |
| News Grid | `NewsIndex` (full-page Livewire) | Grid card berita (`Article` model) — gambar, kategori badge, judul, excerpt (dari `content`), tanggal. Fitur: filter kategori, search `wire:model.live`, pagination `wire:click` |

#### Screen 3: Detail Berita (`/berita/{slug}`)

**Route:** `GET /berita/{article:slug}` → `App\Livewire\Pages\NewsDetail`

| Seksi UI | Livewire Component | Deskripsi |
|----------|--------------------|-----------| 
| Article Header | Inline | Judul, author info (nama dari relasi `author`), tanggal, estimasi waktu baca |
| Featured Image | Inline | Gambar utama artikel (16:9 aspect ratio) |
| Article Body | Inline | Konten WYSIWYG dari kolom `content` (prose styling) |
| SEO Meta | Inline | Meta title (`seo_title`) & description (`seo_description`) |
| Sidebar: Berita Terkait | `RelatedNews` (Blade partial) | 3 card berita terkait (berdasarkan kategori) |
| Sidebar: Tag Populer | `PopularTags` (Blade partial) | Tag cloud dari model `Tag` |

#### Screen 4: Publikasi & Dokumen (`/publikasi`)

**Route:** `GET /publikasi` → `App\Livewire\Pages\PublicationIndex`

| Seksi UI | Livewire Component | Deskripsi |
|----------|--------------------|-----------| 
| Page Header | Inline | Judul "Publikasi & Dokumen" + subtitle |
| Document List | `PublicationIndex` (full-page Livewire) | List/grid dokumen: judul, deskripsi, tipe file (`file_type`), ukuran (`file_size`), tipe publikasi (relasi `tipe`), tombol unduh. Fitur: filter tipe, search, pagination |

#### Screen 5: Struktur Organisasi (`/profil/struktur-organisasi`)

**Route:** `GET /profil/struktur-organisasi` → `App\Livewire\Pages\OrganizationStructure`

| Seksi UI | Livewire Component | Deskripsi |
|----------|--------------------|-----------| 
| Page Header | Inline | Judul "Struktur Organisasi" + subtitle |
| Leader Card | Inline | Card besar Kepala Dinas (foto, nama, jabatan dari relasi `position`, departemen dari relasi `departement`) |
| Hierarchy Chart | Inline | Bagan organisasi menggunakan self-referencing `parent_id` dari model `OrganizationMember` → `children()` relation |

#### Screen 6 & 7: Hubungi Kami (`/kontak`)

**Route:** `GET /kontak` → `App\Livewire\Pages\ContactPage`

> **Status:** Model `ContactMessage` dan `Faq` belum diimplementasi. Perlu dibuat migration, model, dan Filament resource.

| Seksi UI | Livewire Component | Deskripsi |
|----------|--------------------|-----------| 
| Page Header | Inline | Judul "Hubungi Kami" + subtitle |
| Contact Form | `ContactForm` (Livewire) | Form: Nama, Email, Subjek, Pesan + validasi + rate limiting |
| Contact Info | Blade partial | Alamat, Telepon, Email dari `SiteSetting` model |
| FAQ Accordion | `FaqSection` (Blade partial, Alpine.js) | Daftar pertanyaan populer dengan expand/collapse |

### 2.3 Filament Resources (Backend CMS) — Status Implementasi

| Filament Resource | Model | Navigation Group | Status | Fitur Utama |
|-------------------|-------|-------------------|--------|-------------|
| `ArticleResource` | `Article` | Berita | ✅ Sudah Ada | CRUD artikel, RichEditor, upload gambar, kategori, tag, status (`ArticleStatus` enum), SEO fields, role-based filtering |
| `CategoryResource` | `Category` | Berita | ✅ Sudah Ada | CRUD kategori berita (slug, description) |
| `PublicationResource` | `Publication` | Publication | ✅ Sudah Ada | CRUD publikasi, upload file, tipe (`Tipe` model), status (`PublicationStatus` enum), auto file_type/file_size |
| `TipeResource` | `Tipe` | Publication | ✅ Sudah Ada | CRUD tipe publikasi (name, slug) |
| `TagResource` | `Tag` | — | ⚠️ Inline saja | Tag bisa dibuat inline dari `ArticleForm`. Belum ada dedicated resource |
| `DepartementResource` | `Departement` | — | ✅ Sudah Ada | CRUD departemen/bidang organisasi |
| `PositionResource` | `Position` | — | ✅ Sudah Ada | CRUD jabatan organisasi (name, level) |
| `OrganizationMemberResource` | `OrganizationMember` | — | ✅ Sudah Ada | CRUD anggota organisasi, jabatan (FK), departemen (FK), hierarki (parent_id), foto upload |
| `UserResource` | `User` | System Management | ✅ Sudah Ada | CRUD user, **hanya akses Admin** (`canAccess()` check) |
| `ManageSettings` (Page) | `SiteSetting` | System Management | ✅ Sudah Ada | Pengaturan situs (tabs: General/Contact/Social Media), file upload logo/favicon |
| `ContactMessageResource` | `ContactMessage` | — | ❌ Belum Ada | **Perlu dibuat:** View & manage pesan masuk |
| `FaqResource` | `Faq` | — | ❌ Belum Ada | **Perlu dibuat:** CRUD FAQ |
| `GalleryResource` | `Gallery` | — | ❌ Belum Ada | **Perlu dibuat:** CRUD galeri (opsional) |

---

## 3. Database Schema & Relational Mapping

### 3.1 Entity Relationship Diagram

```
┌──────────┐     ┌──────────────┐     ┌───────────┐
│  users   │────<│   articles   │>────│categories │
│          │     │              │     │           │
│ id       │     │ id           │     │ id        │
│ name     │     │ author_id(FK)│     │ name      │
│ email    │     │ category_id  │     │ slug      │
│ password │     │ title        │     │ description│
│ role     │     │ slug         │     └───────────┘
└──────┬───┘     │ content      │
       │         │ status       │     ┌───────────┐
       │         │ publish_at   │<>───│   tags    │
       │         │ views        │     │           │
       │         │ seo_title    │     │ id        │
       │         │ seo_desc     │     │ name      │
       │         └──────────────┘     │ slug      │
       │                              └───────────┘
       │         ┌──────────────┐
       │────────<│ publications │     ┌───────────────────┐
       │         │              │     │ article_tags      │
       │         │ id           │     │ (pivot)           │
       │         │ user_id (FK) │     │                   │
       │         │ tipe_id (FK) │     │ article_id        │
       │         │ title, slug  │     │ tag_id            │
       │         │ file_path    │     └───────────────────┘
       │         │ file_type    │
       │         │ file_size    │     ┌───────────┐
       │         │ status       │     │  tipes    │
       │         │ download_cnt │>────│           │
       │         └──────────────┘     │ id        │
       │                              │ name      │
       │                              │ slug      │
       │                              └───────────┘
       │
       │         ┌───────────────────────┐
       │         │ organization_members  │
       │         │                       │
       │         │ id                    │
       │         │ name                  │
       │         │ position_id ──────>─ positions (id, name, level)
       │         │ departement_id ────>─ departements (id, name, description)
       │         │ parent_id (self-ref)  │
       │         │ bio, photo            │
       │         │ sort_order, is_active │
       │         └───────────────────────┘
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
    $table->string('role')->default('author'); -- admin | publisher | author
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
    $table->text('description')->nullable();
    $table->timestamps();

    $table->index('slug');
});
```

> **Catatan:** Berbeda dengan plan awal, `categories` **tidak** memiliki kolom `type` atau `sort_order`. Kategori hanya digunakan untuk artikel (`Article`). Publikasi menggunakan model `Tipe` terpisah.

#### `articles`
```sql
Schema::create('articles', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('slug')->unique();
    $table->longText('content');  -- bukan 'body' atau 'excerpt'
    $table->string('featured_image')->nullable();
    $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
    $table->string('status')->default('draf');
    $table->string('seo_title')->nullable();
    $table->text('seo_description')->nullable();
    $table->timestamp('publish_at')->nullable();  -- bukan 'published_at'
    $table->unsignedBigInteger('views')->default(0);  -- bukan 'views_count'
    $table->timestamps();

    $table->index('status');
    $table->index('title');
});
```

> **Perbedaan penting dari plan awal:**
> - Tabel bernama `articles` (bukan `news`)
> - FK `author_id` (bukan `user_id`)
> - Kolom `content` (bukan `body` + `excerpt`)
> - Kolom `publish_at` (bukan `published_at`)
> - Kolom `views` (bukan `views_count`)
> - Kolom `seo_title` dan `seo_description` (tambahan, tidak di plan awal)
> - **Tidak ada** kolom `is_featured` dan `version` (optimistic locking)

#### `tags`
```sql
Schema::create('tags', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->timestamps();
});
```

#### `article_tags` (pivot)
```sql
Schema::create('article_tags', function (Blueprint $table) {
    $table->foreignId('article_id')->constrained()->cascadeOnDelete();
    $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
    $table->primary(['article_id', 'tag_id']);
});
```

> **Catatan:** Pivot table bernama `article_tags` (bukan `news_tag`). Memiliki model `ArticleTag`.

#### `tipes`
```sql
Schema::create('tipes', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->timestamps();

    $table->index('slug');
});
```

> **Catatan:** Model `Tipe` menggantikan relasi `category_id` pada `publications`. Ini memisahkan tipe publikasi dari kategori berita.

#### `publications`
```sql
Schema::create('publications', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('tipe_id')->constrained('tipes')->cascadeOnDelete();  -- bukan category_id
    $table->string('title');
    $table->string('slug')->unique();
    $table->string('description');  -- bukan text/nullable
    $table->string('file_path');
    $table->string('file_type');
    $table->bigInteger('file_size');
    $table->string('status')->default('draf');
    $table->timestamp('published_at')->nullable();
    $table->integer('download_count')->default(0);
    $table->timestamps();

    $table->index(['status', 'published_at'], 'idx_publications_status_published');
});
```

> **Catatan:** `file_type` dan `file_size` diisi otomatis via `booted()` saving event berdasarkan `file_path`.

#### `departements`
```sql
Schema::create('departements', function (Blueprint $table) {
    $table->id();
    $table->string('name')->unique();
    $table->text('description')->nullable();
    $table->timestamps();
});
```

#### `positions`
```sql
Schema::create('positions', function (Blueprint $table) {
    $table->id();
    $table->string('name')->unique();
    $table->integer('level')->default(0);
    $table->timestamps();
});
```

#### `organization_members`
```sql
Schema::create('organization_members', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->foreignId('position_id')->nullable()->constrained('positions')->nullOnDelete();
    $table->foreignId('departement_id')->nullable()->constrained('departements')->nullOnDelete();
    $table->string('bio')->nullable();  -- string bukan text
    $table->string('photo')->nullable();
    $table->foreignId('parent_id')->nullable()->constrained('organization_members')->nullOnDelete();
    $table->integer('sort_order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();

    $table->index(['parent_id', 'sort_order'], 'idx_org_members_parent_sort');
});
```

> **Perbedaan dari plan awal:** `position` dan `department` bukan kolom string; mereka adalah FK ke tabel terpisah `positions` dan `departements`.

#### `site_settings`
```sql
Schema::create('site_settings', function (Blueprint $table) {
    $table->id();
    $table->string('key')->unique();
    $table->text('value')->nullable();
    $table->string('group')->default('general');  -- general, contact, social
    $table->timestamps();

    $table->index('group', 'idx_site_setting_group');
});
```

### 3.3 Tabel yang Belum Ada (Perlu Dibuat)

Tabel berikut ada di plan awal tetapi **belum diimplementasi**:

| Tabel | Status | Prioritas |
|-------|--------|-----------|
| `contact_messages` | ❌ Belum ada | **Tinggi** — diperlukan untuk halaman Kontak |
| `faqs` | ❌ Belum ada | **Tinggi** — diperlukan untuk halaman Kontak |
| `galleries` | ❌ Belum ada | Sedang — opsional, bisa ditambahkan nanti |
| `gallery_items` | ❌ Belum ada | Sedang — opsional, bisa ditambahkan nanti |

### 3.4 Tabel Spatie Permission — TIDAK DIGUNAKAN

Plan awal mereferensikan Spatie Permission (`roles`, `permissions`, `model_has_roles`, dll.). **Package ini tidak diinstal dan tidak digunakan.** RBAC dikelola melalui kolom sederhana `role` pada tabel `users`.

---

## 4. Role-Based Access Control (RBAC) Strategy

### 4.1 Implementasi RBAC — Simple Role Column

Berbeda dengan plan awal yang menggunakan Spatie Permission, project ini menggunakan **kolom `role`** pada model `User`:

```php
// app/Models/User.php
class User extends Authenticatable implements FilamentUser
{
    protected $fillable = ['name', 'email', 'password', 'role'];

    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->role, ['admin', 'publisher', 'author']);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
```

### 4.2 Definisi Role

| Role | Scope | Deskripsi |
|------|-------|-----------|
| **admin** | Full Access | Akses penuh ke seluruh fitur CMS termasuk manajemen user dan pengaturan situs |
| **publisher** | Artikel + Publikasi | Bisa membuat, mengedit, publish/unpublish artikel & publikasi |
| **author** | Artikel + Publikasi (own) | Bisa membuat (draft) dan mengedit artikel miliknya sendiri. **Tidak bisa** mengubah status ke `publish` atau `archived` |

### 4.3 Access Control Implementation (Sudah Ada)

#### Filament Resource Level

```php
// UserResource — hanya admin
public static function canAccess(): bool
{
    return Auth::user()?->role === 'admin';
}
```

#### Query Scoping — Author Only Sees Own Articles

```php
// ArticlesTable.php
->modifyQueryUsing(function (Builder $query) {
    if (Auth::user()->role === 'author') {
        return $query->where('author_id', Auth::id());
    }
    return $query;
})
```

#### Form Level — Disable Publish/Archive untuk Author

```php
// ArticleForm.php
Select::make('status')
    ->options(ArticleStatus::class)
    ->required()
    ->disableOptionWhen(fn(string $value): bool =>
        Auth::user()->role === 'author' &&
        in_array($value, [ArticleStatus::PUBLISH->value, ArticleStatus::ARCHIVED->value])
    ),
```

### 4.4 Alur Kerja Konten (Article Status Workflow)

`ArticleStatus` enum memiliki **5 status** (lebih kaya dari plan awal yang hanya 3):

```
Author membuat artikel
       │
       ▼
  [Status: DRAF]
       │
       ▼
Author submit untuk review
       │
       ▼
  [Status: PENDING_REVIEW]
       │
       ├── Publisher/Admin approve → [Status: PUBLISH]
       │
       └── Publisher/Admin minta revisi → [Status: CHANGES_REQUESTED]
       │                                         │
       │                                         ▼
       │                                  Author revisi → kembali ke PENDING_REVIEW
       │
       ▼
  Publisher/Admin bisa arsipkan
       │
       ▼
  [Status: ARCHIVED]
```

### 4.5 Article Status Enum (Sudah Diimplementasi)

```php
// app/Enums/ArticleStatus.php
enum ArticleStatus: string implements HasLabel, HasColor
{
    case DRAF = 'draf';
    case PENDING_REVIEW = 'pending_review';
    case CHANGES_REQUESTED = 'change_requested';
    case PUBLISH = 'publish';
    case ARCHIVED = 'archived';
}
```

### 4.6 Publication Status Enum (Sudah Diimplementasi)

```php
// app/Enums/PublicationStatus.php
enum PublicationStatus: string implements HasLabel, HasColor
{
    case DRAF = 'draf';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';
}
```

### 4.7 Yang Belum Diimplementasi

| Fitur | Status | Catatan |
|-------|--------|---------|
| Policy classes (`ArticlePolicy`, `PublicationPolicy`) | ❌ Belum ada | Saat ini hanya query filter dan form-level restriction |
| Middleware authorization | ❌ Belum ada | Perlu ditambahkan untuk route publik yang butuh auth |
| Role-based navigation visibility | ⚠️ Partial | Hanya `UserResource` yang ada `canAccess()` |

---

## 5. Security & Data Integrity

### 5.1 XSS Prevention

**Risiko utama:** Input RichEditor (content artikel) mengandung HTML mentah.

| Strategi | Status | Implementasi |
|----------|--------|-------------|
| **Sanitasi Input** | ❌ Belum ada | Perlu `mews/purifier` untuk membersihkan HTML dari RichEditor |
| **Output Escaping** | Rencana | Untuk `content`, gunakan `{!! $article->content !!}` hanya setelah sanitasi |
| **CSP Headers** | ❌ Belum ada | Perlu ditambahkan |

### 5.2 File Upload — Sudah Diimplementasi

Model `Article`, `Publication`, dan `OrganizationMember` sudah memiliki **booted events** untuk:

- **Deleting:** Hapus file dari storage saat record dihapus
- **Updating:** Hapus file lama dari storage saat file di-replace
- **Saving (Publication):** Auto-detect `file_type` dan `file_size` dari `file_path`

```php
// Contoh dari Article model
protected static function booted()
{
    static::deleting(function (Article $article) {
        if ($article->featured_image && Storage::disk('public')->exists($article->featured_image)) {
            Storage::disk('public')->delete($article->featured_image);
        }
    });
}
```

### 5.3 Mass Assignment

> ⚠️ **Perhatian:** Model `Article` dan `Tag` memiliki `protected $guarded = []` bersamaan dengan `$fillable`. Ini redundant dan `$guarded = []` mem-bypass `$fillable`. Sebaiknya hapus `$guarded = []`.

### 5.4 Rate Limiting (Belum Diimplementasi)

```php
// Rencana untuk routes/web.php
Route::post('/kontak', ContactForm::class)
    ->middleware('throttle:5,1'); // 5 submit per menit
```

### 5.5 File Upload Validation (via Filament Form)

```php
// Sudah diimplementasi di ArticleForm
FileUpload::make('featured_image')
    ->image()
    ->disk('public')
    ->directory('articles')
    ->preserveFilenames()
    ->imageEditor(),
```

---

## 6. Performance & Potential Issues

### 6.1 N+1 Query Problem — Ditangani

Eager loading **sudah diimplementasi** di `ArticlesTable`:

```php
// app/Filament/Resources/Articles/Tables/ArticlesTable.php
->modifyQueryUsing(fn(Builder $query) => $query->with('author', 'category', 'tags'))
```

#### Perlu ditambahkan untuk Frontend Livewire:

```php
// Homepage — load artikel terbaru
$latestArticles = Article::query()
    ->with(['category:id,name,slug', 'author:id,name'])
    ->where('status', ArticleStatus::PUBLISH->value)
    ->latest('publish_at')
    ->limit(3)
    ->get();

// Publikasi terbaru
$latestPublications = Publication::query()
    ->with(['tipe:id,name'])  // bukan category
    ->where('status', PublicationStatus::PUBLISHED->value)
    ->latest('published_at')
    ->limit(4)
    ->get();
```

### 6.2 Model::preventLazyLoading (Belum)

```php
// Perlu ditambahkan di AppServiceProvider
public function boot(): void
{
    Model::preventLazyLoading(! app()->isProduction());
}
```

### 6.3 Race Condition: Download Counter

```php
// ✅ BENAR — Atomic increment
Publication::query()
    ->where('id', $publication->id)
    ->increment('download_count');
```

### 6.4 Caching Strategy

**Sudah Diimplementasi:**

| Data | Implementasi | Invalidation |
|------|-------------|--------------|
| Site Settings | `Cache::rememberForever()` per key | Event-driven (`saved`/`deleted` on `SiteSetting`) |

**Belum Diimplementasi (Rencana):**

| Data | Cache Driver | TTL | Invalidation |
|------|-------------|-----|-------------|
| Homepage (latest articles + publications) | File | 10 menit | Observer saat konten di-publish |
| Daftar Kategori | File | 60 menit | Saat kategori di-CRUD |
| Struktur Organisasi | File | 24 jam | Saat data organisasi diubah |
| Single Article | File | 30 menit | Saat artikel di-update |

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

### 7.5 Environment Variables Checklist (`.env`)

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
QUEUE_CONNECTION=sync

MAIL_MAILER=smtp
MAIL_HOST=smtp.example.go.id
MAIL_PORT=587
MAIL_USERNAME=noreply@diskominfo.go.id
MAIL_PASSWORD=<mail-password>
MAIL_ENCRYPTION=tls

FILAMENT_PATH=admin
```

### 7.6 Post-Deployment Steps

```bash
# 1. Migrasi database
php artisan migrate --force

# 2. Seed admin user
php artisan db:seed --class=AdminSeeder

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

## Lampiran A: File & Folder Structure (Aktual)

```
app/
├── Enums/
│   ├── ArticleStatus.php            ✅ (5 status: draf, pending_review, change_requested, publish, archived)
│   └── PublicationStatus.php        ✅ (3 status: draf, published, archived)
├── Filament/
│   ├── Pages/
│   │   └── ManageSettings.php       ✅ (Site Settings Page)
│   └── Resources/
│       ├── Articles/                ✅ (ArticleResource + Pages + Schemas + Tables)
│       ├── Categories/              ✅
│       ├── Departements/            ✅
│       ├── OrganizationMembers/     ✅
│       ├── Positions/               ✅
│       ├── Publications/            ✅ (PublicationResource + Pages + Schemas + Tables)
│       ├── Tipes/                   ✅
│       └── Users/                   ✅ (Admin only)
├── Http/
│   └── Controllers/                 (kosong)
├── Models/
│   ├── Article.php                  ✅
│   ├── ArticleTag.php               ✅ (pivot model)
│   ├── Category.php                 ✅
│   ├── Departement.php              ✅
│   ├── OrganizationMember.php       ✅
│   ├── Position.php                 ✅
│   ├── Publication.php              ✅
│   ├── SiteSetting.php              ✅ (with caching)
│   ├── Tag.php                      ✅
│   ├── Tipe.php                     ✅
│   └── User.php                     ✅ (FilamentUser, simple role)
└── Providers/
    └── AppServiceProvider.php

database/
├── migrations/
│   ├── 0001_01_01_000000_create_users_table.php
│   ├── 0001_01_01_000001_create_cache_table.php
│   ├── 0001_01_01_000002_create_jobs_table.php
│   ├── 2026_03_13_153307_create_categories_table.php
│   ├── 2026_03_14_042859_create_articles_table.php
│   ├── 2026_03_15_024737_create_tags_table.php
│   ├── 2026_03_15_025210_create_article_tags_table.php
│   ├── 2026_03_16_014956_create_tipes_table.php
│   ├── 2026_03_16_021716_create_publications_table.php
│   ├── 2026_03_17_173051_create_departements_table.php
│   ├── 2026_03_17_173253_create_positions_table.php
│   ├── 2026_03_17_173254_create_organization_members_table.php
│   └── 2026_03_27_022619_create_site_settings_table.php
├── factories/
│   └── UserFactory.php
└── seeders/
    ├── AdminSeeder.php
    └── DatabaseSeeder.php

resources/views/
└── (frontend belum diimplementasi — only welcome.blade.php)

routes/
├── web.php           (hanya '/' → welcome view)
└── console.php
```

## Lampiran B: Rencana Frontend Livewire (Belum Diimplementasi)

```
app/Livewire/                            (belum ada)
├── Pages/
│   ├── HomePage.php
│   ├── NewsIndex.php
│   ├── NewsDetail.php
│   ├── PublicationIndex.php
│   ├── OrganizationStructure.php
│   └── ContactPage.php
└── Components/
    ├── LatestNews.php
    ├── ContactForm.php
    └── NewsletterSubscription.php

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
```

## Lampiran C: Backlog — Fitur yang Perlu Ditambahkan

| # | Fitur | Prioritas | Estimasi |
|---|-------|-----------|----------|
| 1 | Migration `contact_messages` | Tinggi | 1 jam |
| 2 | Model `ContactMessage` + Filament Resource | Tinggi | 2 jam |
| 3 | Migration `faqs` | Tinggi | 1 jam |
| 4 | Model `Faq` + Filament Resource | Tinggi | 2 jam |
| 5 | Hapus `$guarded = []` dari `Article` dan `Tag` | Tinggi | 5 menit |
| 6 | `Model::preventLazyLoading()` di `AppServiceProvider` | Sedang | 5 menit |
| 7 | Policy classes (`ArticlePolicy`, `PublicationPolicy`) | Sedang | 3 jam |
| 8 | Frontend Livewire (semua halaman publik) | Tinggi | 3-5 hari |
| 9 | XSS sanitasi (`mews/purifier`) | Sedang | 2 jam |
| 10 | Gallery system (migration, model, resource) | Rendah | 4 jam |
| 11 | Newsletter subscription | Rendah | 3 jam |
| 12 | `is_featured` kolom pada articles (jika dibutuhkan) | Rendah | 1 jam |
| 13 | Caching layer untuk frontend | Sedang | 3 jam |
| 14 | `canAccess()` pada seluruh Filament Resources | Sedang | 1 jam |

## Lampiran D: Perbedaan Utama Plan Awal vs Implementasi Aktual

| Aspek | Plan Awal | Implementasi Aktual |
|-------|-----------|---------------------|
| **Model Berita** | `News` | `Article` |
| **Kolom konten** | `body` + `excerpt` | `content` (satu kolom) |
| **FK penulis** | `user_id` | `author_id` |
| **Kolom tanggal terbit** | `published_at` | `publish_at` |
| **Kolom views** | `views_count` | `views` |
| **Featured flag** | `is_featured` | Tidak ada |
| **Optimistic locking** | `version` column | Tidak ada |
| **SEO fields** | Tidak ada | `seo_title`, `seo_description` |
| **Status artikel** | 3 (draft, published, archived) | 5 (draf, pending_review, change_requested, publish, archived) |
| **Pivot table** | `news_tag` | `article_tags` |
| **RBAC** | Spatie Permission 6.x | Simple `role` column |
| **Publikasi FK** | `category_id` | `tipe_id` (FK ke `tipes`) |
| **Organisasi position** | String column `position` | FK `position_id` ke `positions` table |
| **Organisasi department** | String column `department` | FK `departement_id` ke `departements` table |
| **Kategori `type`** | `type` column (news/publication) | Tidak ada — hanya untuk articles |
| **Filament resource** | `SiteSettingResource` | `ManageSettings` Page |
| **Galeri** | `Gallery` + `GalleryItem` models | Belum ada |
| **Kontak** | `ContactMessage` model | Belum ada |
| **FAQ** | `Faq` model | Belum ada |
| **Resource structure** | Flat (`Resources/NewsResource.php`) | Nested (`Resources/Articles/ArticleResource.php` + subdirectories) |

