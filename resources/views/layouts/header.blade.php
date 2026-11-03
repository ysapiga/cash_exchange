@php
    $headerPhone = isset($exchangePoints) && $exchangePoints->isNotEmpty() ? trim(explode(',', $exchangePoints->first()->telephone)[0]) : null;
@endphp
<header class="sticky top-0 z-50 bg-ink/90 backdrop-blur-md border-b border-[#232A3B]">
    <div class="max-w-[1200px] mx-auto px-4 md:px-6 py-3 flex items-center gap-8">
        <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0">
            <img src="{{ asset('images/cash_new_without_background.png') }}" alt="Cash47" class="w-10 h-10 md:w-12 md:h-12 object-contain">
            <span class="font-display font-bold text-lg md:text-xl tracking-tight text-white">Cash<span class="text-lime">47</span></span>
        </a>

        <nav aria-label="Основна навігація" class="hidden lg:flex gap-7 flex-1">
            <a href="#rates" class="text-soft hover:text-white font-semibold text-[15px] transition-colors">Курси</a>
            <a href="#calc" class="text-soft hover:text-white font-semibold text-[15px] transition-colors">Калькулятор</a>
            <a href="#conv" class="text-soft hover:text-white font-semibold text-[15px] transition-colors">Конвертація</a>
            <a href="#points" class="text-soft hover:text-white font-semibold text-[15px] transition-colors">Пункти обміну</a>
        </nav>

        <div class="flex items-center gap-5 ml-auto">
            @if($headerPhone)
            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $headerPhone) }}" class="hidden md:inline text-white font-bold text-[15px] tabular-nums hover:text-lime transition-colors">{{ $headerPhone }}</a>
            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $headerPhone) }}" aria-label="Подзвонити" class="md:hidden w-11 h-11 rounded-full bg-raised border border-[#2E3650] inline-flex items-center justify-center text-lime">
                <svg aria-hidden="true" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/></svg>
            </a>
            @endif
            <button type="button" onclick="openModal()" class="hidden md:inline-flex items-center gap-2 min-h-11 px-5 rounded-full bg-lime text-ink font-extrabold text-[15px] hover:bg-[#93D662] active:scale-95 transition-all">
                <svg aria-hidden="true" class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/></svg>
                Замовити дзвінок
            </button>
        </div>
    </div>
</header>

<!-- Модальне вікно -->
<div id="contactModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-[9999]">
    <div class="bg-paper text-ink rounded-[28px] p-7 w-full max-w-md mx-4 shadow-2xl">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="font-display font-bold text-2xl tracking-tight">Замовити дзвінок</h2>
                <p class="text-paper-muted text-sm mt-1">Ми передзвонимо вам найближчим часом</p>
            </div>
            <button type="button" onclick="closeModal()" aria-label="Закрити" class="text-paper-muted hover:text-ink hover:bg-[#E2E6DC] rounded-xl w-10 h-10 inline-flex items-center justify-center transition-colors">
                <svg aria-hidden="true" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="contactForm" class="js-contact-form space-y-4">
            @csrf
            <div>
                <label for="contact_name" class="block text-xs font-extrabold uppercase tracking-wider text-paper-muted mb-2">Ім'я</label>
                <input type="text" id="contact_name" name="contact_name" placeholder="Введіть ваше ім'я" required
                    class="w-full min-h-14 px-4 rounded-2xl bg-white border-2 border-[#D5DACE] text-ink placeholder-[#8A92A3] focus:outline-none focus:border-lime transition-colors">
            </div>
            <div>
                <label for="contact_phone" class="block text-xs font-extrabold uppercase tracking-wider text-paper-muted mb-2">Номер телефону</label>
                <input type="tel" id="contact_phone" name="contact_phone" placeholder="+380 XX XXX XX XX" required
                    class="w-full min-h-14 px-4 rounded-2xl bg-white border-2 border-[#D5DACE] text-ink placeholder-[#8A92A3] focus:outline-none focus:border-lime transition-colors">
            </div>
            <button type="submit" class="w-full min-h-14 bg-ink text-white rounded-2xl font-extrabold hover:bg-[#1F2536] active:scale-[0.98] transition-all mt-2">
                Відправити
            </button>
        </form>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('contactModal').classList.remove('hidden');
        document.getElementById('contactModal').classList.add('flex');
        document.body.style.overflow = 'hidden';
        var map = document.getElementById('exchange-map');
        if (map) map.style.visibility = 'hidden';
    }

    function closeModal() {
        document.getElementById('contactModal').classList.add('hidden');
        document.getElementById('contactModal').classList.remove('flex');
        document.body.style.overflow = '';
        var map = document.getElementById('exchange-map');
        if (map) map.style.visibility = 'visible';
    }

    document.getElementById('contactModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // Every contact form on the page (modal + inline CTA block) posts the same way
    document.addEventListener('submit', function(e) {
        var form = e.target.closest('.js-contact-form');
        if (!form) return;
        e.preventDefault();

        fetch('{{ route('contact-requests.store') }}', {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            closeModal();
            form.reset();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Помилка при відправці форми');
        });
    });
</script>
