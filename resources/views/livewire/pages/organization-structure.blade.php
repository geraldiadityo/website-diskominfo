<div>
    {{-- Page Header --}}
    <section class="bg-gradient-to-r from-primary-600 to-primary-800 py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-primary-200 font-medium text-sm tracking-wider uppercase mb-2">{{ $jumbotron['jumbotron_organisasi_subtitle'] }}</p>
            <h1 class="text-3xl lg:text-4xl font-bold text-white">{{ $jumbotron['jumbotron_organisasi_title'] }}</h1>
        </div>
    </section>

    {{-- Content --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
        @forelse($members as $leader)
            {{-- Leader Card --}}
            <div class="flex flex-col items-center">
                <div class="bg-gradient-to-br from-primary-50 to-primary-100 border-2 border-primary-200 rounded-2xl p-6 text-center max-w-sm w-full shadow-sm relative z-10">
                    @if($leader->photo)
                        <img src="{{ Storage::url($leader->photo) }}" alt="{{ $leader->name }}" class="w-24 h-24 rounded-full object-cover mx-auto mb-3 ring-4 ring-white shadow-lg">
                    @else
                        <div class="w-24 h-24 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 mx-auto mb-3 flex items-center justify-center ring-4 ring-white shadow-lg">
                            <span class="material-icons-outlined text-white text-3xl">person</span>
                        </div>
                    @endif
                    <h3 class="font-bold text-slate-900 text-lg">{{ $leader->name }}</h3>
                    <p class="text-primary-700 font-medium text-sm mt-1">{{ $leader->position?->name }}</p>
                </div>

                {{-- Connector --}}
                @if($leader->children->count())
                    <div class="w-0.5 h-10 bg-primary-200"></div>
                @endif
            </div>

            {{-- Level 2: Department Heads --}}
            @if($leader->children->count())
                <div class="flex flex-wrap justify-center gap-y-10 relative">
                    {{-- Horizontal Top Border for Tree (hidden on mobile, acts as connection line) --}}
                    @if($leader->children->count() > 1)
                        <div class="hidden sm:block absolute top-0 left-1/2 -translate-x-1/2 h-0.5 bg-primary-200 shadow-sm" style="width: calc(100% - {{ 100 / max($leader->children->count(), 1) }}%);"></div>
                    @endif

                    @foreach($leader->children as $child)
                        <div wire:key="member-{{ $child->id }}" class="w-full sm:w-1/2 lg:w-1/3 xl:w-1/4 px-3 flex flex-col items-center relative group/child">
                            {{-- Vertical line down to card --}}
                            <div class="hidden sm:block w-0.5 h-6 bg-primary-200 mb-0"></div>
                            
                            <div class="w-full bg-white border border-slate-200 rounded-xl p-5 text-center hover:shadow-lg hover:border-primary-200 transition group relative z-10 shadow-sm">
                                @if($child->photo)
                                    <img src="{{ Storage::url($child->photo) }}" alt="{{ $child->name }}" class="w-20 h-20 rounded-full object-cover mx-auto mb-3 ring-2 ring-slate-100">
                                @else
                                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-slate-200 to-slate-300 mx-auto mb-3 flex items-center justify-center">
                                        <span class="material-icons-outlined text-slate-500 text-2xl">person</span>
                                    </div>
                                @endif
                                <h4 class="font-bold text-slate-900 text-sm group-hover:text-primary-700 transition-colors">{{ $child->name }}</h4>
                                <p class="text-primary-600 text-xs font-medium mt-1">{{ $child->position?->name }}</p>
                                @if($child->departement)
                                    <p class="text-slate-500 text-xs mt-1">{{ $child->departement->name }}</p>
                                @endif

                                {{-- Level 3: Children of department heads --}}
                                @if($child->children->count())
                                    <div class="mt-4 pt-4 border-t border-slate-100 space-y-3 text-left">
                                        @foreach($child->children as $grandchild)
                                            <div class="flex items-center gap-3" wire:key="member-{{ $grandchild->id }}">
                                                @if($grandchild->photo)
                                                    <img src="{{ Storage::url($grandchild->photo) }}" alt="" class="w-10 h-10 rounded-full object-cover shrink-0">
                                                @else
                                                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center shrink-0">
                                                        <span class="material-icons-outlined text-slate-400 text-sm">person</span>
                                                    </div>
                                                @endif
                                                <div class="min-w-0">
                                                    <p class="text-xs font-medium text-slate-700 truncate">{{ $grandchild->name }}</p>
                                                    <p class="text-xs text-slate-400 truncate">{{ $grandchild->position?->name }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @empty
            <div class="text-center py-16 text-slate-400">
                <span class="material-icons-outlined text-6xl mb-3">groups</span>
                <p class="text-lg font-medium">Belum ada data struktur organisasi</p>
            </div>
        @endforelse
    </section>
</div>
