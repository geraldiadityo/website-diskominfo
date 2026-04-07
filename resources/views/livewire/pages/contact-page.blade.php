<div>
    {{-- Page Header --}}
    <section class="bg-gradient-to-r from-primary-600 to-primary-800 py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-primary-200 font-medium text-sm tracking-wider uppercase mb-2">{{ $jumbotron['jumbotron_kontak_subtitle'] }}</p>
            <h1 class="text-3xl lg:text-4xl font-bold text-white">{{ $jumbotron['jumbotron_kontak_title'] }}</h1>
        </div>
    </section>

    {{-- Content --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">
            {{-- Contact Form --}}
            <div class="lg:col-span-2">
                <div class="bg-white border border-slate-100 rounded-2xl p-6 lg:p-8 shadow-sm">
                    <h2 class="text-xl font-bold text-slate-900 mb-6">Kirim Pesan</h2>

                    @if($submitted)
                        <div class="bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl p-6 text-center">
                            <span class="material-icons-outlined text-4xl text-emerald-500 mb-2">check_circle</span>
                            <h3 class="font-bold text-lg mb-1">Pesan Terkirim!</h3>
                            <p class="text-sm">Terima kasih atas pesan Anda. Kami akan merespons dalam 1-2 hari kerja.</p>
                            <button wire:click="$set('submitted', false)" class="mt-4 text-sm text-emerald-600 font-medium hover:underline">Kirim pesan lagi</button>
                        </div>
                    @else
                        <form wire:submit="submit" class="space-y-5">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">Nama Lengkap</label>
                                    <input wire:model="name" type="text" id="name"
                                           class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 outline-none transition text-sm"
                                           placeholder="Nama Anda">
                                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                                    <input wire:model="email" type="email" id="email"
                                           class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 outline-none transition text-sm"
                                           placeholder="email@contoh.com">
                                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div>
                                <label for="subject" class="block text-sm font-medium text-slate-700 mb-1.5">Subjek</label>
                                <input wire:model="subject" type="text" id="subject"
                                       class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 outline-none transition text-sm"
                                       placeholder="Perihal pesan">
                                @error('subject') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="message" class="block text-sm font-medium text-slate-700 mb-1.5">Pesan</label>
                                <textarea wire:model="message" id="message" rows="5"
                                          class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 outline-none transition text-sm resize-y"
                                          placeholder="Tulis pesan Anda..."></textarea>
                                @error('message') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <button type="submit"
                                    class="inline-flex items-center gap-2 bg-primary-600 text-white font-semibold px-6 py-3 rounded-lg hover:bg-primary-700 transition"
                                    wire:loading.attr="disabled"
                                    wire:loading.class="opacity-50 cursor-not-allowed">
                                <span wire:loading.remove wire:target="submit" class="material-icons-outlined text-lg">send</span>
                                <span wire:loading wire:target="submit" class="material-icons-outlined text-lg animate-spin">autorenew</span>
                                Kirim Pesan
                            </button>
                        </form>
                    @endif
                </div>

                {{-- FAQ --}}
                @if($faqs->count())
                    <div class="mt-10">
                        <h2 class="text-xl font-bold text-slate-900 mb-6">Pertanyaan yang Sering Diajukan</h2>
                        <div class="space-y-3">
                            @foreach($faqs as $faq)
                                <div x-data="{ open: false }" wire:key="faq-{{ $faq->id }}" class="bg-white border border-slate-100 rounded-xl overflow-hidden">
                                    <button @click="open = !open" class="w-full flex items-center justify-between p-5 text-left">
                                        <span class="font-semibold text-slate-900 text-sm pr-4">{{ $faq->question }}</span>
                                        <span class="material-icons-outlined text-slate-400 shrink-0 transition-transform" :class="{ 'rotate-180': open }">expand_more</span>
                                    </button>
                                    <div x-show="open" x-collapse>
                                        <div class="px-5 pb-5 text-sm text-slate-600 leading-relaxed border-t border-slate-50 pt-3">
                                            {!! nl2br(e($faq->answer)) !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Contact Info Sidebar --}}
            <div class="space-y-6">
                <div class="bg-gradient-to-br from-primary-50 to-primary-100 rounded-2xl p-6">
                    <h3 class="font-bold text-slate-900 mb-4">Informasi Kontak</h3>
                    <div class="space-y-4 text-sm">
                        <div class="flex items-start gap-3">
                            <span class="material-icons-outlined text-primary-600 text-xl mt-0.5">location_on</span>
                            <span class="text-slate-700">{{ $contactAddress }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="material-icons-outlined text-primary-600 text-xl">phone</span>
                            <span class="text-slate-700">{{ $contactPhone }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="material-icons-outlined text-primary-600 text-xl">email</span>
                            <span class="text-slate-700">{{ $contactEmail }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50 rounded-2xl p-6">
                    <h3 class="font-bold text-slate-900 mb-4">Jam Operasional</h3>
                    <div class="space-y-2 text-sm text-slate-600">
                        <div class="flex justify-between">
                            <span>Senin - Kamis</span>
                            <span class="font-medium text-slate-800">07:30 – 16:00</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Jumat</span>
                            <span class="font-medium text-slate-800">07:30 – 16:30</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Sabtu - Minggu</span>
                            <span class="font-medium text-red-500">Tutup</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
