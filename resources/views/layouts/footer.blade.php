<footer class="border-t border-[#232A3B] pb-24 md:pb-0">
    <div class="max-w-[1200px] mx-auto px-4 md:px-6 py-12 flex flex-col md:flex-row flex-wrap gap-10 justify-between items-start">

        <div class="flex items-center gap-3">
            <img src="{{ asset('images/cash_new_without_background.png') }}" alt="Cash47" class="w-14 h-14 object-contain">
            <div>
                <div class="font-display font-bold text-xl">Cash<span class="text-lime">47</span></div>
                <div class="text-sm text-muted">Обмін валют, Мукачево</div>
            </div>
        </div>

        @if(isset($exchangePoints) && $exchangePoints->isNotEmpty())
        <div class="flex flex-col gap-2.5">
            <h3 class="text-xs font-extrabold uppercase tracking-[0.12em] text-muted mb-1">Контакти</h3>
            @foreach($exchangePoints as $point)
                @foreach(array_map('trim', explode(',', $point->telephone)) as $phone)
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}" class="text-white hover:text-lime font-semibold tabular-nums transition-colors">{{ $phone }}</a>
                @endforeach
            @endforeach
        </div>
        @endif

        @if($socialLinks?->instagram || $socialLinks?->telegram || $socialLinks?->facebook)
        <div class="flex flex-col gap-2.5">
            <h3 class="text-xs font-extrabold uppercase tracking-[0.12em] text-muted mb-1">Соціальні мережі</h3>
            @if($socialLinks?->instagram)
            <a href="{{ $socialLinks->instagram }}" target="_blank" rel="noopener" class="text-white hover:text-lime font-semibold transition-colors">Instagram</a>
            @endif
            @if($socialLinks?->telegram)
            <a href="{{ $socialLinks->telegram }}" target="_blank" rel="noopener" class="text-white hover:text-lime font-semibold transition-colors">Telegram</a>
            @endif
            @if($socialLinks?->facebook)
            <a href="{{ $socialLinks->facebook }}" target="_blank" rel="noopener" class="text-white hover:text-lime font-semibold transition-colors">Facebook</a>
            @endif
        </div>
        @endif

    </div>

    <div class="max-w-[1200px] mx-auto px-4 md:px-6 pt-5 pb-8 border-t border-[#1C2232] text-[13px] text-[#8E97AA]">
        © {{ date('Y') }} Cash47 — Обмін валют Мукачево
    </div>
</footer>
