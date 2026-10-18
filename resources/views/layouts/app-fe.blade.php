<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>@yield('title') | PMB UMGO</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;700;800&family=Figtree:wght@400;500;600&display=swap" rel="stylesheet">
<link
	rel="icon"
	type="image/svg+xml"
	href='{{ asset('logo.png') }}'
/>
@vite(['resources/css/app.css', 'resources/js/app.js'])
<style>
	html { scroll-behavior: smooth; scroll-padding-top: 5rem; }
	body { background: #fff; color: #1e293b; }
	.slide { transition: opacity .7s ease; }
	@media (prefers-reduced-motion: reduce) { .slide { transition: none; } html { scroll-behavior: auto; } }
	a:focus-visible, button:focus-visible { outline: 3px solid #93c5fd; outline-offset: 2px; }
</style>
@stack('styles')
</head>
<body class="font-sans antialiased">
	@include('partials.navbar')
	<main id="home">@yield('content')</main>
	@include('partials.footer')
	@include('partials.wa-float')
	@stack('scripts')
</body>
</html>
