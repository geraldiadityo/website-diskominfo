<footer class="bg-slate-900 text-slate-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12 stagger-children">

            {{-- Info --}}
            <div class="lg:col-span-1 animate-on-scroll">
                <div class="flex items-center gap-2 mb-4">
                    <span class="material-icons-outlined text-primary-400 text-2xl">account_balance</span>
                    <span class="font-bold text-lg text-white">{{ $siteName }}</span>
                </div>
                <p class="text-sm leading-relaxed text-slate-400 mb-3">{{ $contactAddress }}</p>
                @if($contactAddress)
                <a href="https://maps.google.com/?q={{ urlencode($contactAddress) }}" target="_blank" rel="noopener" class="block w-full h-28 rounded-lg overflow-hidden relative group mb-4 shadow bg-slate-800">
                    <iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q={{ urlencode($contactAddress) }}&t=&z=13&ie=UTF8&iwloc=&output=embed" class="pointer-events-none opacity-70 group-hover:opacity-100 group-hover:scale-105 transition-all duration-500"></iframe>
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="bg-slate-900/80 text-white text-xs font-medium px-3 py-1.5 rounded-full backdrop-blur-sm flex items-center gap-1 shadow-lg">
                            <span class="material-icons-outlined text-[14px]">open_in_new</span> Buka Map
                        </div>
                    </div>
                </a>
                @endif
                <div class="space-y-2 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="material-icons-outlined text-base text-slate-500">email</span>
                        <span>{{ $contactEmail }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="material-icons-outlined text-base text-slate-500">phone</span>
                        <span>{{ $contactPhone }}</span>
                    </div>
                </div>
            </div>

            {{-- Peta Situs --}}
            <div class="animate-on-scroll">
                <h4 class="font-semibold text-white text-sm mb-4">Peta Situs</h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="/" class="hover:text-primary-400 transition-colors">Beranda</a></li>
                    <li><a href="/profil" class="hover:text-primary-400 transition-colors">Tentang Kami</a></li>
                    <li><a href="/profil/struktur-organisasi" class="hover:text-primary-400 transition-colors">Struktur Organisasi</a></li>
                    <li><a href="/berita" class="hover:text-primary-400 transition-colors">Berita & Media</a></li>
                    <li><a href="/publikasi" class="hover:text-primary-400 transition-colors">Publikasi</a></li>
                    <li><a href="/kontak" class="hover:text-primary-400 transition-colors">Hubungi Kami</a></li>
                </ul>
            </div>

            {{-- Link Terkait --}}
            <div class="animate-on-scroll">
                <h4 class="font-semibold text-white text-sm mb-4">Link Terkait</h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="#" class="hover:text-primary-400 transition-colors">Portal Pemerintah Daerah</a></li>
                    <li><a href="#" class="hover:text-primary-400 transition-colors">PPID</a></li>
                    <li><a href="#" class="hover:text-primary-400 transition-colors">LAPOR!</a></li>
                    <li><a href="#" class="hover:text-primary-400 transition-colors">Indonesia.go.id</a></li>
                </ul>
            </div>

            {{-- Sosial Media --}}
            <div class="animate-on-scroll">
                <h4 class="font-semibold text-white text-sm mb-4">Ikuti Kami</h4>
                <div class="flex items-center gap-3">
                    @if($socialFacebook)
                        <a href="{{ $socialFacebook }}" target="_blank" rel="noopener" class="w-10 h-10 rounded-lg bg-slate-800 flex items-center justify-center hover:bg-primary-600 transition-colors">
                            <span class="material-icons-outlined text-lg">public</span>
                        </a>
                    @endif
                    @if($socialInstagram)
                        <a href="{{ $socialInstagram }}" target="_blank" rel="noopener" class="w-10 h-10 rounded-lg bg-slate-800 flex items-center justify-center hover:bg-primary-600 transition-colors">
                            <span class="material-icons-outlined text-lg">photo_camera</span>
                        </a>
                    @endif
                    <a href="mailto:{{ $contactEmail }}" class="w-10 h-10 rounded-lg bg-slate-800 flex items-center justify-center hover:bg-primary-600 transition-colors">
                        <span class="material-icons-outlined text-lg">alternate_email</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Copyright --}}
    <div class="border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <p class="text-sm text-slate-500 text-center">
                &copy; {{ date('Y') }} {{ $siteName }}. Hak Cipta Dilindungi.
            </p>
        </div>
    </div>
</footer>
