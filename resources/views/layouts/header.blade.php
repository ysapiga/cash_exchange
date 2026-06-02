<header class="bg-[#262b3b]/95 backdrop-blur-sm shadow-lg sticky top-0 z-50 border-b border-[#5F963B]/30">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-20">
            <div class="lg:scale-[1.3] lg:translate-y-[28%] translate-y-[10%] flex items-center">
                <img src="{{ asset('images/cash_new_without_background.png') }}" alt="Cash47" class="w-32 h-auto">
            </div>
            <button onclick="openModal()" class="group relative bg-[#5F963B] text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-[#6faa45] active:scale-95 transition-all duration-200 shadow-lg shadow-[#5F963B]/25 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                Замовити
            </button>
        </div>
    </div>
</header>

<!-- Модальне вікно -->
<div id="contactModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-[9999]">
    <div class="bg-[#262b3b] border border-gray-600/50 rounded-2xl p-7 w-full max-w-md mx-4 shadow-2xl">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-bold text-white">Замовити дзвінок</h2>
                <p class="text-gray-400 text-sm mt-0.5">Ми передзвонимо вам найближчим часом</p>
            </div>
            <button onclick="closeModal()" class="text-gray-500 hover:text-white hover:bg-gray-700 rounded-lg p-1.5 transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="contactForm" class="space-y-4">
            @csrf
            <div>
                <label for="contact_name" class="block text-sm font-medium text-gray-300 mb-1.5">Ім'я</label>
                <input type="text" id="contact_name" name="contact_name" placeholder="Введіть ваше ім'я" required
                    class="w-full px-4 py-2.5 rounded-xl bg-gray-800/80 border border-gray-600/60 text-white placeholder-gray-500 focus:outline-none focus:border-[#5F963B] focus:ring-1 focus:ring-[#5F963B]/50 transition-all duration-200">
            </div>
            <div>
                <label for="contact_phone" class="block text-sm font-medium text-gray-300 mb-1.5">Номер телефону</label>
                <input type="tel" id="contact_phone" name="contact_phone" placeholder="+380 XX XXX XX XX" required
                    class="w-full px-4 py-2.5 rounded-xl bg-gray-800/80 border border-gray-600/60 text-white placeholder-gray-500 focus:outline-none focus:border-[#5F963B] focus:ring-1 focus:ring-[#5F963B]/50 transition-all duration-200">
            </div>
            <button type="submit" class="w-full bg-[#5F963B] text-white px-6 py-3 rounded-xl font-semibold hover:bg-[#6faa45] active:scale-[0.98] transition-all duration-200 shadow-lg shadow-[#5F963B]/20 mt-2">
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

    document.getElementById('contactForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('{{ route('contact-requests.store') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            closeModal();
            this.reset();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Помилка при відправці форми');
        });
    });
</script>