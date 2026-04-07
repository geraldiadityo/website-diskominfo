<div>
    {{-- Page Header --}}
    <section class="bg-gradient-to-r from-primary-600 to-primary-800 py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-primary-200 font-medium text-sm tracking-wider uppercase mb-2">{{ $jumbotron['jumbotron_profil_subtitle'] }}</p>
            <h1 class="text-3xl lg:text-4xl font-bold text-white">{{ $jumbotron['jumbotron_profil_title'] }}</h1>
        </div>
    </section>

    {{-- Content --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16 space-y-12">

        {{-- Visi --}}
        @if($visi)
        <div>
            <div class="flex items-center gap-3 mb-6">
                <div class="bg-primary-100 text-primary-600 p-2.5 rounded-xl">
                    <span class="material-icons-outlined text-2xl">visibility</span>
                </div>
                <h2 class="text-2xl font-bold text-slate-900">Visi</h2>
            </div>
            <div class="bg-gradient-to-r from-primary-50 to-white border border-primary-100 rounded-2xl p-6 lg:p-8">
                <div class="prose-article text-lg text-slate-700 leading-relaxed italic">{!! $visi !!}</div>
            </div>
        </div>
        @endif

        {{-- Misi --}}
        @if($misi)
        <div>
            <div class="flex items-center gap-3 mb-6">
                <div class="bg-emerald-100 text-emerald-600 p-2.5 rounded-xl">
                    <span class="material-icons-outlined text-2xl">flag</span>
                </div>
                <h2 class="text-2xl font-bold text-slate-900">Misi</h2>
            </div>
            <div class="bg-slate-50 rounded-2xl p-6 lg:p-8">
                <div class="prose-article">{!! $misi !!}</div>
            </div>
        </div>
        @endif

        {{-- Sejarah --}}
        @if($sejarah)
        <div>
            <div class="flex items-center gap-3 mb-6">
                <div class="bg-amber-100 text-amber-600 p-2.5 rounded-xl">
                    <span class="material-icons-outlined text-2xl">history_edu</span>
                </div>
                <h2 class="text-2xl font-bold text-slate-900">Sejarah</h2>
            </div>
            <div class="bg-slate-50 rounded-2xl p-6 lg:p-8">
                <div class="prose-article">{!! $sejarah !!}</div>
            </div>
        </div>
        @endif

        {{-- Tupoksi --}}
        @if($tupoksi)
        <div>
            <div class="flex items-center gap-3 mb-6">
                <div class="bg-purple-100 text-purple-600 p-2.5 rounded-xl">
                    <span class="material-icons-outlined text-2xl">assignment</span>
                </div>
                <h2 class="text-2xl font-bold text-slate-900">Tugas Pokok & Fungsi</h2>
            </div>
            <div class="bg-slate-50 rounded-2xl p-6 lg:p-8">
                <div class="prose-article">{!! $tupoksi !!}</div>
            </div>
        </div>
        @endif

        {{-- If no content at all --}}
        @if(!$visi && !$misi && !$sejarah && !$tupoksi)
            <div class="text-center py-16 text-slate-400">
                <span class="material-icons-outlined text-6xl mb-3">info</span>
                <p class="text-lg font-medium">Halaman profil belum memiliki konten</p>
                <p class="text-sm">Silakan tambahkan data melalui panel admin.</p>
            </div>
        @endif
    </section>
</div>
