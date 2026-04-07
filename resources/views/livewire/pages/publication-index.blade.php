<div>
    {{-- Page Header --}}
    <section class="bg-gradient-to-r from-primary-600 to-primary-800 py-12 lg:py-16 hero-shimmer relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-primary-200 font-medium text-sm tracking-wider uppercase mb-2 animate-slide-in-left delay-100">{{ $jumbotron['jumbotron_publikasi_subtitle'] }}</p>
            <h1 class="text-3xl lg:text-4xl font-bold text-white animate-slide-in-left delay-200">{{ $jumbotron['jumbotron_publikasi_title'] }}</h1>
            <p class="text-primary-100 mt-2 max-w-2xl animate-slide-in-left delay-300">{{ $jumbotron['jumbotron_publikasi_desc'] }}</p>
        </div>
    </section>

    {{-- Content --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{-- Search & Filter --}}
        <div class="flex flex-col md:flex-row gap-4 mb-8 animate-slide-up delay-200">
            <div class="relative flex-1">
                <span class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xl">search</span>
                <input wire:model.live.debounce.300ms="search"
                       type="text"
                       placeholder="Cari dokumen..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 outline-none transition text-sm">
            </div>
        </div>

        {{-- Tipe Filter --}}
        <div class="flex flex-wrap gap-2 mb-8 animate-slide-up delay-300">
            <button wire:click="$set('tipeSlug', '')"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $tipeSlug === '' ? 'bg-primary-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Semua
            </button>
            @foreach($tipes as $t)
                <button wire:click="$set('tipeSlug', '{{ $t->slug }}')"
                        wire:key="tipe-{{ $t->id }}"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $tipeSlug === $t->slug ? 'bg-primary-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    {{ $t->name }} ({{ $t->publications_count }})
                </button>
            @endforeach
        </div>

        {{-- Publications List --}}
        <div class="space-y-4 stagger-children">
            @forelse($publications as $pub)
                <div wire:key="pub-{{ $pub->id }}" class="bg-white border border-slate-100 rounded-xl p-5 hover:border-primary-200 hover:shadow-md transition group animate-on-scroll">
                    <div class="flex items-start gap-4">
                        <div class="bg-red-100 text-red-600 p-3 rounded-xl shrink-0">
                            <span class="material-icons-outlined text-2xl">picture_as_pdf</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-slate-900 group-hover:text-primary-700 transition-colors">{{ $pub->title }}</h3>
                            @if($pub->description)
                                <p class="text-sm text-slate-500 mt-1 line-clamp-2">{{ $pub->description }}</p>
                            @endif
                            <div class="flex flex-wrap items-center gap-3 mt-3 text-xs text-slate-400">
                                @if($pub->tipe)
                                    <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded font-medium">{{ $pub->tipe->name }}</span>
                                @endif
                                <span>{{ $pub->created_at->format('d M Y') }}</span>
                                <span>{{ $pub->download_count ?? 0 }} unduhan</span>
                            </div>
                        </div>
                        @if($pub->file_path)
                            <button wire:click="download({{ $pub->id }})" class="shrink-0 inline-flex items-center gap-1.5 bg-primary-600 text-white text-sm font-medium px-4 py-2 rounded-lg hover:bg-primary-700 transition">
                                <span class="material-icons-outlined text-base">download</span> Unduh
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-16 text-slate-400 animate-fade-in">
                    <span class="material-icons-outlined text-6xl mb-3">folder_off</span>
                    <p class="text-lg font-medium">Tidak ada publikasi ditemukan</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $publications->links() }}
        </div>
    </section>
</div>
