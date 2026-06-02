<footer class="bg-[#262b3b] border-t border-[#5F963B]/30 mt-10">
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row justify-between items-start gap-8">

            <div>
                <h3 class="text-sm font-semibold text-[#7dc44a] uppercase tracking-wider mb-4">Контакти</h3>
                <div class="space-y-1">
                    @foreach($exchangePoints as $point)
                    @foreach(array_map('trim', explode(',', $point->telephone)) as $phone)
                    <a href="tel:{{ $phone }}" class="flex items-center gap-2.5 text-gray-300 hover:text-white transition-colors duration-200 group py-1">
                        <div class="w-8 h-8 rounded-lg bg-gray-700/60 group-hover:bg-[#5F963B]/20 flex items-center justify-center transition-colors duration-200 shrink-0">
                            <svg class="w-4 h-4 text-[#5F963B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <span class="text-sm">{{ $phone }}</span>
                    </a>
                    @endforeach
                    <a href="https://www.google.com/maps?q={{ $point->coordinates }}" target="_blank" class="flex items-center gap-2.5 text-gray-300 hover:text-white transition-colors duration-200 group py-1">
                        <div class="w-8 h-8 rounded-lg bg-gray-700/60 group-hover:bg-[#5F963B]/20 flex items-center justify-center transition-colors duration-200 shrink-0">
                            <svg class="w-4 h-4 text-[#5F963B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <span class="text-sm">{{ $point->address }}</span>
                    </a>
                    @endforeach
                </div>
            </div>

            @if($socialLinks?->instagram || $socialLinks?->telegram || $socialLinks?->facebook)
            <div>
                <h3 class="text-sm font-semibold text-[#7dc44a] uppercase tracking-wider mb-4">Соціальні мережі</h3>
                <div class="flex flex-col gap-2">
                    @if($socialLinks?->instagram)
                    <a href="{{ $socialLinks->instagram }}" target="_blank" class="flex items-center gap-2.5 text-gray-300 hover:text-white transition-colors duration-200 group">
                        <div class="w-8 h-8 rounded-lg bg-gray-700/60 group-hover:bg-[#5F963B]/20 flex items-center justify-center transition-colors duration-200">
                            <svg class="w-4 h-4 text-[#5F963B]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.012-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </div>
                        <span class="text-sm">Instagram</span>
                    </a>
                    @endif
                    @if($socialLinks?->telegram)
                    <a href="{{ $socialLinks->telegram }}" target="_blank" class="flex items-center gap-2.5 text-gray-300 hover:text-white transition-colors duration-200 group">
                        <div class="w-8 h-8 rounded-lg bg-gray-700/60 group-hover:bg-[#5F963B]/20 flex items-center justify-center transition-colors duration-200">
                            <svg class="w-4 h-4 text-[#5F963B]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69.01-.03.01-.14-.07-.2-.08-.06-.19-.04-.27-.02-.11.02-1.93 1.23-5.46 3.62-.52.36-.99.53-1.41.52-.46-.01-1.35-.26-2.01-.48-.81-.27-1.46-.42-1.4-.89.03-.25.37-.51 1.03-.78 4.04-1.76 6.74-2.92 8.09-3.48 3.85-1.6 4.64-1.88 5.17-1.89.11 0 .37.03.54.17.14.12.18.28.2.45-.02.14-.02.3-.03.42z"/>
                            </svg>
                        </div>
                        <span class="text-sm">Telegram</span>
                    </a>
                    @endif
                    @if($socialLinks?->facebook)
                    <a href="{{ $socialLinks->facebook }}" target="_blank" class="flex items-center gap-2.5 text-gray-300 hover:text-white transition-colors duration-200 group">
                        <div class="w-8 h-8 rounded-lg bg-gray-700/60 group-hover:bg-[#5F963B]/20 flex items-center justify-center transition-colors duration-200">
                            <svg class="w-4 h-4 text-[#5F963B]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </div>
                        <span class="text-sm">Facebook</span>
                    </a>
                    @endif
                </div>
            </div>
            @endif

        </div>

        <div class="mt-8 pt-6 border-t border-gray-700/40 text-center text-gray-500 text-xs">
            © {{ date('Y') }} Cash47 — Обмін валют Мукачево
        </div>
    </div>
</footer>
