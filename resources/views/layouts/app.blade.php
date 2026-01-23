
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
</head>
<body class="font-sans antialiased bg-[url('/images/cash_background_enhanced.png')] bg-cover bg-center bg-fixed">
    @include('layouts.header')
    <main class="container mx-auto px-4 py-8">
        @yield('content')
    </main>
    @include('layouts.footer')
</body>
</html>
