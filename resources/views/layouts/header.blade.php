<header class="bg-[#282741] shadow-sm sticky top-0 z-50">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-20">
            <div class="flex items-center">
                <div class="w-56 h-16 rounded-xl bg-gradient-to-br from-emerald-600 to-green-600 flex items-center justify-center overflow-hidden bg-[url('/images/cash.png')] bg-cover bg-center bg-no-repeat">
                </div>
            </div>
            <button onclick="openModal()" class="bg-[#94b13c] text-white px-6 py-2 rounded-lg hover:bg-[#8aa336] transition-all duration-300 md:h-auto h-11 w-40 md:w-auto flex items-center justify-center text-center">
                Замовити
            </button>
        </div>
    </div>
</header>

<!-- Модальне вікно -->
<div id="contactModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-[#282741] rounded-xl p-6 w-full max-w-md mx-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-white">Замовити дзвінок</h2>
            <button onclick="closeModal()" class="text-gray-400 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="contactForm" class="space-y-4">
            @csrf
            <div>
                <label for="contact_name" class="block text-sm font-medium text-gray-300 mb-1">Ім'я</label>
                <input type="text" id="contact_name" name="contact_name" required class="w-full px-4 py-2 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:border-[#94b13c]">
            </div>
            <div>
                <label for="contact_phone" class="block text-sm font-medium text-gray-300 mb-1">Номер телефону</label>
                <input type="tel" id="contact_phone" name="contact_phone" required class="w-full px-4 py-2 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:border-[#94b13c]">
            </div>
            <button type="submit" class="w-full bg-[#94b13c] text-white px-6 py-2 rounded-lg hover:bg-[#8aa336] transition-all duration-300">
                Відправити
            </button>
        </form>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('contactModal').classList.remove('hidden');
        document.getElementById('contactModal').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('contactModal').classList.add('hidden');
        document.getElementById('contactModal').classList.remove('flex');
    }

    // Закриття модального вікна при кліку поза ним
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