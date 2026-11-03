
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Unbounded:wght@500;700;800&display=swap">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body class="font-sans antialiased bg-ink text-white min-h-screen">
    <div class="min-h-screen flex flex-col">
    @include('layouts.header')
    <main class="flex-1">
        @yield('content')
    </main>
    @include('layouts.footer')

    {{-- Sticky mobile CTA --}}
    <div id="sticky-cta" class="md:hidden fixed bottom-0 inset-x-0 z-40 translate-y-full transition-transform duration-300 ease-out">
        <div class="bg-ink/95 backdrop-blur-sm border-t border-line px-4 pt-3 pb-[max(0.75rem,env(safe-area-inset-bottom))] flex gap-2.5">
            @php $ctaPhone = isset($exchangePoints) && $exchangePoints->isNotEmpty() ? trim(explode(',', $exchangePoints->first()->telephone)[0]) : null; @endphp
            @if($ctaPhone)
            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $ctaPhone) }}" class="flex-1 min-h-[52px] rounded-2xl border border-[#2E3650] text-white font-extrabold text-[15px] flex items-center justify-center">
                Подзвонити
            </a>
            @endif
            <button onclick="openModal()" class="flex-[1.4] min-h-[52px] rounded-2xl bg-lime text-ink font-extrabold text-[15px] flex items-center justify-center active:scale-[0.98] transition-transform">
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
