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

                {{-- Footer Info: Tags & Share --}}
                <div class="mt-8 pt-6 border-t border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    {{-- Tags --}}
                    @if($article->tags->count())
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-sm font-medium text-slate-600">Tag:</span>
                            @foreach($article->tags as $tag)
                                <span class="bg-slate-100 text-slate-600 text-xs font-medium px-3 py-1 rounded-full">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    @else
                        <div></div> {{-- Empty placeholder to keep layout consistent --}}
                    @endif

                    {{-- Share Buttons --}}
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-medium text-slate-600">Bagikan:</span>
                        
                        {{-- WhatsApp --}}
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($article->title . ' - ' . url()->current()) }}" target="_blank" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-600 hover:bg-[#25D366] hover:text-white transition" title="Bagikan ke WhatsApp">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </a>

                        {{-- Facebook --}}
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-600 hover:bg-[#1877F2] hover:text-white transition" title="Bagikan ke Facebook">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>

                        {{-- Twitter / X --}}
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode(url()->current()) }}" target="_blank" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-600 hover:bg-black hover:text-white transition" title="Bagikan ke X (Twitter)">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>

                        {{-- Copy Link --}}
                        <button x-data="{ copied: false }" @click="$event.preventDefault(); if (navigator.clipboard &amp;&amp; window.isSecureContext) { navigator.clipboard.writeText(window.location.href); } else { let t = document.createElement('textarea'); t.value = window.location.href; t.style.position = 'fixed'; t.style.opacity = '0'; document.body.appendChild(t); t.select(); document.execCommand('copy'); document.body.removeChild(t); } copied = true; setTimeout(() => copied = false, 2000)" class="relative w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-600 hover:bg-primary-600 hover:text-white transition" title="Salin Tautan">
                            <span x-show="!copied" class="material-icons-outlined text-sm">link</span>
                            <span x-show="copied" x-cloak class="material-icons-outlined text-sm text-white">check</span>
                            <div x-show="copied" x-cloak x-transition class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[10px] px-2 py-1 rounded whitespace-nowrap">Tersalin!</div>
                        </button>
                    </div>
                </div>
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
