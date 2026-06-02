
<!DOCTYPE html>
<html lang="uk">
<head>
    	<script type="application/ld+json">
		{
		"@context": "http://schema.org",
		"@type": "WebSite",
		"url": "https://cash47.com.ua/",
		"name": "Послуги з обміну валют. Усі види валютних операцій. Обмін валют Мукачево",
		"description": "Послуги з обміну валют. Усі види валютних операцій. Обмін валют Мукачево",
		"thumbnailUrl": "https://cash47.com.ua/images/ms-icon-70x70.png",
		"keywords": "кеш 47, кеш47, обмін валют мукачево, курс долара, продати долар, курс євро, продати євро, купити форінт, продати форінт",
		"about": {
		"name": "Послуги з обміну валют.",
		"description": "Послуги з обміну валют. Усі види валютних операцій. Обмін валют Мукачево. Cash47"
		},
		"author": "Cash47 - Валютний сервіс. Обмін валют Мукачево",
		"image": "https://cash47.com.ua/images/ms-icon-70x70.png",
		"potentialAction": {
		"@type": "SearchAction",
		"target": "/?s={s}",
		"query-input": "required name=s"
		}
		}
	</script>
    <meta charset="utf-8">
    <meta name="keywords" content="Cash47 обмін валют Мукачево, курс валют Мукачево, купити долар, продати долар, курс євро, купити євро, обмін форінтів, Cash47, валютний обмін Мукачево, готівковий обмін валюти">
    <meta name="description" content="Обмін валют у Мукачеві. Курси долара, євро, форінта. Купівля та продаж готівкової валюти за вигідними умовами. Cash47 — надійний валютний сервіс.">
    <link rel="icon" type="image/png" href="{{ asset('images/ms-icon-70x70.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cash47 — Обмін валют Мукачево</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body class="font-sans antialiased bg-cover bg-center bg-fixed min-h-screen" style="background-image: url('/images/background_new.jpg');">
    <div class="min-h-screen bg-[#1a1e2b]/60 flex flex-col">
    @include('layouts.header')
    <main class="container mx-auto px-4 py-8 flex-1">
        @yield('content')
    </main>
    @include('layouts.footer')

    {{-- Sticky mobile CTA --}}
    <div id="sticky-cta" class="md:hidden fixed bottom-0 inset-x-0 z-40 translate-y-full transition-transform duration-300 ease-out">
        <div class="bg-[#1e2333]/90 backdrop-blur-sm border-t border-[#5F963B]/30 px-4 py-3 safe-area-inset-bottom">
            <button onclick="openModal()" class="w-full bg-[#5F963B] hover:bg-[#6faa45] active:scale-[0.98] text-white font-semibold py-3.5 rounded-xl flex items-center justify-center gap-2 transition-all duration-200 shadow-lg shadow-[#5F963B]/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                Замовити дзвінок
            </button>
        </div>
    </div>
    <script>
    (function () {
        var bar = document.getElementById('sticky-cta');
        var shown = false;
        window.addEventListener('scroll', function () {
            if (window.scrollY > 120 && !shown) {
                bar.classList.remove('translate-y-full');
                shown = true;
            } else if (window.scrollY <= 120 && shown) {
                bar.classList.add('translate-y-full');
                shown = false;
            }
        }, { passive: true });
    })();
    </script>

    </div>
</body>
</html>
