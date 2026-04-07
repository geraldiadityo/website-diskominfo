<div>
    {{-- Page Header --}}
    <section class="bg-gradient-to-br from-primary-600 to-primary-800 py-12 lg:py-16 hero-shimmer relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-primary-200 font-medium text-sm tracking-wider uppercase mb-2 animate-slide-in-left delay-100">{{ $jumbotron['jumbotron_berita_subtitle'] }}</p>
            <h1 class="text-3xl lg:text-4xl font-bold text-white animate-slide-in-left delay-200">{{ $jumbotron['jumbotron_berita_title'] }}</h1>
        </div>
    </section>

    {{-- Content --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{-- Search & Filter --}}
        <div class="flex flex-col md:flex-row gap-4 mb-8 animate-slide-up delay-200">
            <div class="relative flex-1">
                <span
                    class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xl">search</span>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari berita..."
                    class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 outline-none transition text-sm">
            </div>
        </div>

        {{-- Category Tabs --}}
        <div class="flex flex-wrap gap-2 mb-8 animate-slide-up delay-300">
            <button wire:click="$set('categorySlug', '')"
                class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $categorySlug === '' ? 'bg-primary-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Semua
            </button>
            @foreach ($categories as $cat)
                <button wire:click="$set('categorySlug', '{{ $cat->slug }}')" wire:key="cat-{{ $cat->id }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $categorySlug === $cat->slug ? 'bg-primary-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    {{ $cat->name }} ({{ $cat->articles_count }})
                </button>
            @endforeach
        </div>

        {{-- Articles Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 stagger-children">
            @forelse($articles as $article)
                <a href="{{ route('berita.show', $article->slug) }}" wire:key="article-{{ $article->id }}"
                    class="bg-white rounded-xl overflow-hidden border border-slate-100 hover:shadow-lg transition-shadow group animate-on-scroll">
                    <div class="aspect-video overflow-hidden">
                        @if ($article->featured_image)
                            <img src="{{ Storage::url($article->featured_image) }}" alt="{{ $article->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div
                                class="w-full h-full bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center">
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
                            class="font-bold text-slate-900 line-clamp-2 mb-2 group-hover:text-primary-700 transition-colors">
                            {{ $article->title }}</h3>
                        <p class="text-sm text-slate-500 line-clamp-2">
                            {{ Str::limit(strip_tags($article->content), 120) }}</p>
                        <div class="flex items-center gap-2 mt-3 text-xs text-slate-400">
                            <span class="material-icons-outlined text-sm">person</span>
                            <span>{{ $article->author?->name }}</span>
                            <span class="mx-1">•</span>
                            <span class="material-icons-outlined text-sm">visibility</span>
                            <span>{{ $article->views ?? 0 }}</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-16 text-slate-400 animate-fade-in">
                    <span class="material-icons-outlined text-6xl mb-3">search_off</span>
                    <p class="text-lg font-medium">Tidak ada berita ditemukan</p>
                    <p class="text-sm">Coba ubah kata kunci atau filter kategori.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $articles->links() }}
        </div>
    </section>
</div>
