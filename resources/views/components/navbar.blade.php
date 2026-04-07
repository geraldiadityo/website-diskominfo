<nav x-data="{ open: false }" class="bg-white/95 backdrop-blur-md border-b border-slate-100 sticky top-0 z-50 animate-slide-down">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            {{-- Logo --}}
            <a href="/" class="flex items-center gap-2 shrink-0">
                @if($siteLogo)
                    <img src="{{ Storage::url($siteLogo) }}" alt="Logo" class="h-8 w-auto">
                @else
                    <span class="material-icons-outlined text-primary-600 text-3xl">account_balance</span>
                @endif
                <span class="font-bold text-lg text-slate-900">{{ $siteName }}</span>
            </a>

            {{-- Desktop Nav --}}
            <div class="hidden md:flex items-center gap-1">
                <x-navbar-link href="/" :active="request()->is('/')">Beranda</x-navbar-link>
                <x-navbar-link href="/profil" :active="request()->is('profil')">Profil</x-navbar-link>
                <x-navbar-link href="/profil/struktur-organisasi" :active="request()->is('profil/struktur-organisasi')">Struktur</x-navbar-link>
                <x-navbar-link href="/berita" :active="request()->is('berita*')">Berita</x-navbar-link>
                <x-navbar-link href="/publikasi" :active="request()->is('publikasi*')">Publikasi</x-navbar-link>
                <x-navbar-link href="/kontak" :active="request()->is('kontak')">Kontak</x-navbar-link>
            </div>

            {{-- Mobile Menu Button --}}
            <button @click="open = !open" class="md:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100 transition">
                <span x-show="!open" class="material-icons-outlined">menu</span>
                <span x-show="open" x-cloak class="material-icons-outlined">close</span>
            </button>
        </div>
    </div>

    {{-- Mobile Nav --}}
    <div x-show="open" x-collapse x-cloak class="md:hidden border-t border-slate-100 bg-white">
        <div class="px-4 py-3 space-y-1">
            <x-navbar-link-mobile href="/" :active="request()->is('/')">Beranda</x-navbar-link-mobile>
            <x-navbar-link-mobile href="/profil" :active="request()->is('profil')">Profil</x-navbar-link-mobile>
            <x-navbar-link-mobile href="/profil/struktur-organisasi" :active="request()->is('profil/struktur-organisasi')">Struktur Organisasi</x-navbar-link-mobile>
            <x-navbar-link-mobile href="/berita" :active="request()->is('berita*')">Berita</x-navbar-link-mobile>
            <x-navbar-link-mobile href="/publikasi" :active="request()->is('publikasi*')">Publikasi</x-navbar-link-mobile>
            <x-navbar-link-mobile href="/kontak" :active="request()->is('kontak')">Kontak</x-navbar-link-mobile>
        </div>
    </div>
</nav>
