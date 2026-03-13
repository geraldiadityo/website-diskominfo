Kamu adalah seorang Senior Software Architect dan ahli ekosistem Laravel (Laravel 12, Livewire 3, Filament, MySQL).

Tugas kamu adalah menganalisis project UI design saya yang ada di Google Stitch dengan project ID 17826962431898095121 dan membuatkan dokumen perencanaan arsitektur teknis yang komprehensif dalam bentuk file `plan.md`.

Aplikasi ini adalah Portal Web Pemerintah Kota (Monolithic).
Konteks Bisnis & Fitur Utama:

- Frontend: Halaman publik berisi Berita, Publikasi, Galeri, dan informasi kota lainnya.
- Backend: Dasbor manajemen konten.
- Autentikasi/Otorisasi: RBAC hanya di sisi Backend (Filament) dengan role: Admin, Publisher, dan Author khusus untuk modul Berita dan Publikasi.

Tech Stack:

- Framework: Laravel 12
- Frontend: Livewire 3 + Tailwind CSS
- Backend: Laravel Filament 4
- Database: MySQL
- Infrastruktur Target: VPS Konvensional (aaPanel)

Di dalam `plan.md`, tolong susun berdasarkan struktur berikut dan perhatikan instruksi spesifik di setiap bagiannya:

1. **Executive Summary & Tech Stack Overview**
    - Ringkasan arsitektur monolitik yang memisahkan ranah Livewire (Frontend) dan Filament (Backend).

2. **UI/UX to Component Mapping (Berdasarkan Desain Google Stitch)**
    - Ekstrak komponen dari desain UI dan petakan menjadi komponen Livewire (misal: `NewsList`, `PublicationDetail`, `HeroGallery`).
    - Tentukan Filament Resources yang perlu dibuat (misal: `NewsResource`, `PublicationResource`).

3. **Database Schema & Relational Mapping**
    - Rancang skema tabel utama (users, news, publications, galleries, categories).
    - Terapkan struktur untuk RBAC (sangat disarankan menggunakan pendekatan standar seperti Spatie Permission untuk integrasi mulus dengan Filament).

4. **Role-Based Access Control (RBAC) Strategy**
    - Definisikan policy dan logic otorisasi untuk Admin, Publisher, dan Author.
    - Contoh alur: Author hanya bisa _draft_ dan edit miliknya, Publisher bisa _publish/unpublish_, Admin memiliki akses penuh.

5. **Security & Data Integrity**
    - Identifikasi kerentanan dan cara mitigasinya: XSS (terutama di input WYSIWYG untuk Berita/Publikasi), SQL Injection, dan Mass Assignment.
    - Rate limiting untuk endpoint pencarian atau form kontak publik.

6. **Performance & Potential Issues (CRITICAL)**
    - **N+1 Query Problem:** Berikan strategi Eager Loading secara spesifik pada komponen Livewire yang menampilkan daftar berita beserta kategori/penulisnya.
    - **Race Conditions:** Walaupun bukan sistem transaksional berat, mitigasi _race condition_ saat dua 'Publisher' mencoba mengubah status _publish_ pada artikel yang sama secara bersamaan (gunakan _optimistic/pessimistic locking_ atau _state workflow_).
    - **Caching Strategy:** Karena ini portal pemerintah yang _read-heavy_, definisikan strategi _caching_ Laravel (misal: Redis/File cache) untuk halaman depan dan daftar publikasi.

7. **Deployment & Infrastructure (aaPanel Context)**
    - Checklist kebutuhan server untuk aaPanel (versi PHP, ekstensi yang dibutuhkan Filament/Livewire).
    - Konfigurasi _symlink_ storage, optimasi route/view, dan manajemen proses (_cron_ untuk _scheduled publication_ atau Supervisor jika menggunakan Queue).

Pastikan `plan.md` ditulis dengan format Markdown yang rapi, profesional, _to-the-point_, dan siap dieksekusi oleh tim _engineering_.
