<div>
    {{-- Hero Section --}}
    <section
        class="relative {{ $heroImage ? 'bg-slate-900' : 'bg-gradient-to-br from-primary-600 via-primary-700 to-primary-900' }} overflow-hidden hero-shimmer">
        @if ($heroImage)
            {{-- Background Image --}}
            <div class="absolute inset-0 animate-fade-in">
                <img src="{{ Storage::url($heroImage) }}" alt="Hero Background"
                    class="w-full h-full object-cover opacity-50">
            </div>
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900/80 via-slate-900/60 to-transparent"></div>
        @else
            {{-- Default Pattern --}}
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0"
                    style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.15&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
                </div>
            </div>
        @endif
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28 relative">
            <div class="max-w-3xl">
                <p
                    class="text-primary-200 font-medium text-sm tracking-wider uppercase mb-4 animate-slide-in-left delay-100">
                    {{ $jumbotron['hero_label'] }}</p>
                <h1
                    class="text-4xl lg:text-5xl xl:text-6xl font-extrabold text-white leading-tight mb-6 animate-slide-in-left delay-200">
                    {{ $jumbotron['hero_title'] }}
                </h1>
                <p class="text-lg text-primary-100 leading-relaxed mb-8 max-w-2xl animate-slide-in-left delay-300">
                    {{ $jumbotron['hero_subtitle'] }}
                </p>
                <div class="flex flex-wrap gap-3 animate-slide-up delay-400">
                    <a href="/berita"
                        class="inline-flex items-center gap-2 bg-white text-primary-700 font-semibold px-6 py-3 rounded-lg hover:bg-primary-50 transition shadow-lg">
                        <span class="material-icons-outlined text-xl">newspaper</span> Berita Terbaru
                    </a>
                    <a href="/kontak"
                        class="inline-flex items-center gap-2 border-2 border-white/30 text-white font-semibold px-6 py-3 rounded-lg hover:bg-white/10 transition">
                        <span class="material-icons-outlined text-xl">mail</span> Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Quick Services --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-10">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 stagger-children">
            @php
                $quickLinks = [
                    [
                        'icon' => 'cloud',
                        'label' => 'Layanan Publik',
                        'color' => 'bg-blue-500',
                        'url' => 'https://sungaipenuhkota.go.id/menu-utama/layanan-publik',
                        'external' => true,
                    ],
                    [
                        'icon' => 'language',
                        'label' => 'Portal E-Gov',
                        'color' => 'bg-emerald-500',
                        'url' => 'https://sungaipenuhkota.go.id/menu-utama/layanan-pegawai',
                        'external' => true,
                    ],
                    [
                        'icon' => 'campaign',
                        'label' => 'Pengumuman',
                        'color' => 'bg-amber-500',
                        'url' => '#',
                        'external' => false,
                    ],
                    [
                        'icon' => 'description',
                        'label' => 'Publikasi',
                        'color' => 'bg-purple-500',
                        'url' => '/publikasi',
                        'external' => false,
                    ],
                ];
            @endphp
            @foreach ($quickLinks as $i => $link)
                <a href="{{ $link['url'] }}"
                    @if ($link['external'] ?? false) target="_blank" rel="noopener noreferrer" @endif
                    class="bg-white rounded-xl shadow-lg p-5 flex items-center gap-4 hover:shadow-xl transition-shadow group cursor-pointer animate-scale-up delay-{{ ($i + 3) * 100 }}">
                    <div
                        class="{{ $link['color'] }} text-white p-3 rounded-lg group-hover:scale-110 transition-transform">
                        <span class="material-icons-outlined text-2xl">{{ $link['icon'] }}</span>
                    </div>
                    <span class="font-semibold text-slate-800 text-sm">{{ $link['label'] }}</span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Leader Profile --}}
    @if ($leader)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
            <div class="text-center mb-10 animate-on-scroll">
                <p class="text-primary-600 font-semibold text-sm tracking-wider uppercase mb-2">
                    {{ $leader_profile_subtitle }}</p>
                <h2 class="text-3xl font-bold text-slate-900">{{ $leader_profile_title }}</h2>
            </div>
            <div
                class="bg-grandient-to-r from-slate-50 to-primary-50 rounded-2xl p-8 lg:p-12 flex flex-col md:flex-row items-center gap-8 animate-on-scroll">
                <div class="shrink-0">
                    @if ($leader->photo)
                        <img src="{{ Storage::url($leader->photo) }}" alt="{{ $leader->name }}"
                            class="w-40 h-40 rounded-2xl object-cover shadow-lg">
                    @else
                        <div
                            class="w-40 h-40 rounded-2xl bg-linear-to-br from-primary-400 to-primary-600 flex items-center justify-center shadow-lg">
                            <span class="material-icons-outlined text-white text-6xl">person</span>
                        </div>
                    @endif
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-900 mb-1">{{ $leader->name }}</h3>
                    <p class="text-primary-600 font-medium mb-4">{{ $leader->position?->name }} —
                        {{ $leader->departement?->name }}</p>
                    <p class="text-slate-600 leading-relaxed">
                        {{ $leader_profile_description }}
                    </p>
                </div>
            </div>
        </section>
    @endif

    {{-- Latest News --}}
    <section class="bg-slate-50 py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-10 animate-on-scroll">
                <div>
                    <p class="text-primary-600 font-semibold text-sm tracking-wider uppercase mb-2">Pusat Informasi</p>
                    <h2 class="text-3xl font-bold text-slate-900">Berita Terbaru</h2>
                </div>
                <a href="/berita"
                    class="hidden sm:inline-flex items-center gap-1 text-primary-600 font-semibold text-sm hover:text-primary-700 transition">
                    Lihat Semua <span class="material-icons-outlined text-lg">arrow_forward</span>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 stagger-children">
                @forelse($articles as $article)
                    <a href="{{ route('berita.show', $article->slug) }}" wire:key="article-{{ $article->id }}"
                        class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-shadow group animate-on-scroll">
                        <div class="aspect-video overflow-hidden">
                            @if ($article->featured_image)
                                <img src="{{ Storage::url($article->featured_image) }}" alt="{{ $article->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div
                                    class="w-full h-full bg-linear-to-br from-primary-100 to-primary-200 flex items-center justify-center">
                                    <span class="material-icons-outlined text-primary-400 text-5xl">article</span>
                                </div>
                            @endif
                        </div>
                        <div class="p-5">
                            <div class="flex items-center gap-2 text-xs text-slate-500 mb-2">
                                @if ($article->category)
                                    <span
                                        class="bg-primary-50 text-primary-700 font-medium px-2 py-0.5 rounded">{{ $article->category->name }}</span>
                                @endif
                                <span>{{ $article->publish_at?->format('d M Y') }}</span>
                            </div>
                            <h3
                                class="font-bold text-slate-900 line-clamp-2 group-hover:text-primary-700 transition-colors">
                                {{ $article->title }}</h3>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center py-12 text-slate-400">
                        <span class="material-icons-outlined text-5xl mb-2">inbox</span>
                        <p>Belum ada berita.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8 text-center sm:hidden">
                <a href="/berita" class="inline-flex items-center gap-1 text-primary-600 font-semibold text-sm">
                    Lihat Semua Berita <span class="material-icons-outlined text-lg">arrow_forward</span>
                </a>
            </div>
        </div>
    </section>

    {{-- Publications --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
        <div class="flex items-center justify-between mb-10 animate-on-scroll">
            <div>
                <p class="text-primary-600 font-semibold text-sm tracking-wider uppercase mb-2">Transparansi Informasi
                </p>
                <h2 class="text-3xl font-bold text-slate-900">Publikasi & Dokumen</h2>
            </div>
            <a href="/publikasi"
                class="hidden sm:inline-flex items-center gap-1 text-primary-600 font-semibold text-sm hover:text-primary-700 transition">
                Lihat Semua <span class="material-icons-outlined text-lg">arrow_forward</span>
            </a>
        </div>
        <p class="text-slate-600 mb-8 max-w-2xl animate-on-scroll">Akses dokumen resmi, laporan tahunan, dan regulasi
            terkini terkait
            kebijakan komunikasi dan informatika daerah.</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 stagger-children">
            @forelse($publications as $pub)
                <div wire:key="pub-{{ $pub->id }}"
                    class="bg-slate-50 rounded-xl p-5 border border-slate-100 hover:border-primary-200 hover:shadow-md transition group animate-on-scroll">
                    <div class="flex items-start gap-3 mb-3">
                        <div class="bg-red-100 text-red-600 p-2 rounded-lg shrink-0">
                            <span class="material-icons-outlined text-xl">picture_as_pdf</span>
                        </div>
                        <div class="min-w-0">
                            <h4
                                class="font-semibold text-slate-900 text-sm line-clamp-2 group-hover:text-primary-700 transition-colors">
                                {{ $pub->title }}</h4>
                            @if ($pub->tipe)
                                <p class="text-xs text-slate-500 mt-1">{{ $pub->tipe->name }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-xs text-slate-400">
                        <span>{{ $pub->file_size ? \App\Helpers\FileHelper::formatSize($pub->file_size) : 'PDF' }}</span>
                        <span>{{ $pub->download_count ?? 0 }} unduhan</span>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-8 text-slate-400">
                    <p>Belum ada publikasi.</p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- Pop-up Section (Pure Livewire - Desain Sesuai Gambar Referensi) --}}
    {{-- Pop-up Section (Pure Livewire) --}}
    @if ($showPopup)
        {{-- Gunakan style z-index yang sangat tinggi agar menutupi elemen lain terlepas dari kompilasi Tailwind --}}
        <div class="fixed inset-0 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-md" style="z-index: 99999;">

            {{-- Wrapper Gambar --}}
            <div class="relative w-auto max-w-[90vw] md:max-w-lg max-h-[85vh] animate-fade-in flex justify-center">

                {{-- Tombol Close --}}
                <button wire:click="closePopup" type="button"
                    class="absolute -top-3 -right-3 md:-top-4 md:-right-4 flex items-center justify-center w-8 h-8 md:w-10 md:h-10 text-slate-800 bg-white hover:bg-slate-200 hover:text-red-500 rounded-full shadow-xl transition-transform hover:scale-105 cursor-pointer ring-4 ring-slate-900/20"
                    title="Tutup" style="z-index: 100000;">
                    <span class="material-icons-outlined text-lg md:text-xl font-bold">close</span>
                </button>

                @php
                    $imagePath = $this->popupImage;
                    if (str_starts_with($imagePath, '[')) {
                        $decoded = json_decode($imagePath, true);
                        $imagePath = is_array($decoded) ? $decoded[0] ?? '' : $imagePath;
                    }
                @endphp

                {{-- Gambar Banner --}}
                @if ($popupUrl)
                    <a href="{{ $popupUrl }}" target="_blank" class="block focus:outline-none">
                        <img src="{{ Storage::url($imagePath) }}" alt="Pengumuman"
                            class="w-auto h-auto max-h-[85vh] object-contain rounded-2xl shadow-2xl block mx-auto border-4 border-white/10">
                    </a>
                @else
                    <img src="{{ Storage::url($imagePath) }}" alt="Pengumuman"
                        class="w-auto h-auto max-h-[85vh] object-contain rounded-2xl shadow-2xl block mx-auto border-4 border-white/10">
                @endif
            </div>
        </div>
    @endif
</div>
