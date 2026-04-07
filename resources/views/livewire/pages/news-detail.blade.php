<div>
    {{-- Page Header --}}
    <section class="bg-gradient-to-r from-primary-600 to-primary-800 py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="text-sm text-primary-200 mb-4 flex items-center gap-2">
                <a href="/" class="hover:text-white transition">Beranda</a>
                <span class="material-icons-outlined text-sm">chevron_right</span>
                <a href="/berita" class="hover:text-white transition">Berita</a>
                <span class="material-icons-outlined text-sm">chevron_right</span>
                <span class="text-white">Detail</span>
            </nav>
            <h1 class="text-2xl lg:text-3xl font-bold text-white leading-tight max-w-4xl">{{ $article->title }}</h1>
            <div class="flex flex-wrap items-center gap-4 mt-4 text-sm text-primary-200">
                <div class="flex items-center gap-1.5">
                    <span class="material-icons-outlined text-base">calendar_today</span>
                    {{ $article->publish_at?->format('d F Y') }}
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="material-icons-outlined text-base">person</span>
                    {{ $article->author?->name }}
                </div>
                @if($article->category)
                    <span class="bg-white/20 px-2.5 py-0.5 rounded text-white text-xs font-medium">{{ $article->category->name }}</span>
                @endif
                <div class="flex items-center gap-1.5">
                    <span class="material-icons-outlined text-base">visibility</span>
                    {{ number_format($article->views ?? 0) }}x dilihat
                </div>
            </div>
        </div>
    </section>

    {{-- Content --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex flex-col lg:flex-row gap-8">
            {{-- Main Content --}}
            <article class="flex-1 min-w-0">
                @if($article->featured_image)
                    <div class="aspect-video rounded-xl overflow-hidden mb-8">
                        <img src="{{ Storage::url($article->featured_image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                    </div>
                @endif

                <div class="prose-article">
                    {!! $article->content !!}
                </div>

                {{-- Tags --}}
                @if($article->tags->count())
                    <div class="mt-8 pt-6 border-t border-slate-200">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-sm font-medium text-slate-600">Tag:</span>
                            @foreach($article->tags as $tag)
                                <span class="bg-slate-100 text-slate-600 text-xs font-medium px-3 py-1 rounded-full">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </article>

            {{-- Sidebar --}}
            <aside class="w-full lg:w-80 shrink-0 space-y-8">
                {{-- Related Articles --}}
                @if($relatedArticles->count())
                    <div class="bg-slate-50 rounded-xl p-6">
                        <h3 class="font-bold text-slate-900 mb-4">Berita Terkait</h3>
                        <div class="space-y-4">
                            @foreach($relatedArticles as $related)
                                <a href="{{ route('berita.show', $related->slug) }}" wire:key="related-{{ $related->id }}" class="flex gap-3 group">
                                    <div class="w-20 h-14 rounded-lg overflow-hidden shrink-0">
                                        @if($related->featured_image)
                                            <img src="{{ Storage::url($related->featured_image) }}" alt="" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-primary-100 flex items-center justify-center">
                                                <span class="material-icons-outlined text-primary-400 text-sm">article</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-slate-900 line-clamp-2 group-hover:text-primary-700 transition-colors">{{ $related->title }}</p>
                                        <p class="text-xs text-slate-400 mt-1">{{ $related->publish_at?->format('d M Y') }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Popular Tags --}}
                @if($popularTags->count())
                    <div class="bg-slate-50 rounded-xl p-6">
                        <h3 class="font-bold text-slate-900 mb-4">Tag Populer</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($popularTags as $tag)
                                <span wire:key="tag-{{ $tag->id }}" class="bg-white text-slate-600 text-xs font-medium px-3 py-1.5 rounded-full border border-slate-200 hover:border-primary-300 hover:text-primary-700 cursor-pointer transition">
                                    {{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </aside>
        </div>
    </section>
</div>
