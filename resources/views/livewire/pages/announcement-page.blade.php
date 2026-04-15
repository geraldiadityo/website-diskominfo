<div>
    {{-- Page Header --}}
    <section class="bg-gradient-to-r from-primary-600 to-primary-800 py-12 lg:py-16 hero-shimmer relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-primary-200 font-medium text-sm tracking-wider uppercase mb-2 animate-slide-in-left delay-100">{{ $jumbotron['jumbotron_pengumuman_subtitle'] }}</p>
            <h1 class="text-3xl lg:text-4xl font-bold text-white animate-slide-in-left delay-200">{{ $jumbotron['jumbotron_pengumuman_title'] }}</h1>
            <p class="text-primary-100 mt-2 max-w-2xl animate-slide-in-left delay-300">Informasi pengumuman resmi dari Dinas Komunikasi dan Informatika</p>
        </div>
    </section>

    {{-- Content --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 stagger-children">
            @forelse($announcements as $announcement)
                <div wire:key="announcement-{{ $announcement->id }}" class="bg-white border border-slate-100 rounded-xl overflow-hidden hover:border-primary-200 hover:shadow-lg transition-all duration-300 group animate-on-scroll">
                    {{-- Title Bar --}}
                    <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                        <div class="flex items-center gap-3">
                            <span class="material-icons-outlined text-primary-600 text-xl">
                                {{ $announcement->isImage() ? 'image' : 'picture_as_pdf' }}
                            </span>
                            <h2 class="font-bold text-slate-900 group-hover:text-primary-700 transition-colors text-lg">
                                {{ $announcement->title }}
                            </h2>
                        </div>
                    </div>

                    {{-- File Preview --}}
                    <div class="p-4">
                        @if($announcement->isImage())
                            {{-- Image Display --}}
                            <a href="{{ asset('storage/' . $announcement->file_path) }}" target="_blank" class="block">
                                <img
                                    src="{{ asset('storage/' . $announcement->file_path) }}"
                                    alt="{{ $announcement->title }}"
                                    class="w-full h-auto max-h-[400px] object-contain rounded-lg bg-slate-50 hover:opacity-95 transition-opacity cursor-pointer"
                                    loading="lazy"
                                >
                            </a>
                        @elseif($announcement->isPdf())
                            {{-- PDF Embed --}}
                            <div class="rounded-lg overflow-hidden border border-slate-200 bg-slate-50">
                                <iframe
                                    src="{{ asset('storage/' . $announcement->file_path) }}#toolbar=1&navpanes=0"
                                    class="w-full h-[400px]"
                                    title="{{ $announcement->title }}"
                                    loading="lazy"
                                ></iframe>
                            </div>
                            <a href="{{ asset('storage/' . $announcement->file_path) }}"
                               target="_blank"
                               class="inline-flex items-center gap-1.5 mt-3 text-sm text-primary-600 hover:text-primary-800 font-medium transition-colors">
                                <span class="material-icons-outlined text-base">open_in_new</span>
                                Buka PDF di tab baru
                            </a>
                        @endif
                    </div>

                    {{-- Footer --}}
                    <div class="px-5 py-3 border-t border-slate-100 bg-slate-50/30">
                        <span class="text-xs text-slate-400">{{ $announcement->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16 text-slate-400 animate-fade-in">
                    <span class="material-icons-outlined text-6xl mb-3">campaign</span>
                    <p class="text-lg font-medium">Belum ada pengumuman</p>
                    <p class="text-sm mt-1">Pengumuman terbaru akan ditampilkan di sini</p>
                </div>
            @endforelse
        </div>
    </section>
</div>
