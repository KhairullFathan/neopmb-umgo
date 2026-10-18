<!doctype html>
<html lang="en" data-theme="light">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link
			rel="icon"
			type="image/svg+xml"
			href='/logo.png'
		/>
		<script>
			// Apply the saved theme before first paint to avoid a flash.
			(function () {
				var t = localStorage.getItem("stisla-theme");
				if (t === "dark" || t === "light") document.documentElement.dataset.theme = t;
			})();
		</script>
		<link rel="preconnect" href="https://fonts.googleapis.com" />
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
		<link
			rel="stylesheet"
			href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
		/>
		<title>@yield('title') | PMB UMGO</title>

		<link rel="stylesheet" href="assets/css/style.css" />
	</head>
	<body>
		<main class="auth">
            @yield('content')

			<aside class="auth__aside relative overflow-hidden">
				<a href="/meridian/" class="auth__brand">
					<span class="auth__brand-mark">
						<img src="/logo.png" alt="Universitas Muhammadiyah Gorontalo">
					</span>
					<span class="auth__brand-text">
						<span class="auth__brand-name">
                            Universitas <br/>
                            Muhammadiyah Gorontalo
                        </span>
					</span>
				</a>
				<div class="auth__pitch z-100">
					<h2 class="auth__pitch-title text-white text-shadow-lg">Kuliah di UMGO <span class="text-white">Imannya Kuat, Ilmunya Tinggi, Akhlaknya Baik, Soft Skillnya Hebat.</span></h2>
					<p class="auth__pitch-lede text-shadow-lg">
						Cerdas · Integritas · Inovatif · Transparan · Religius · Amanah · Akuntabel
					</p>
				</div>
                <div class="absolute -bottom-2 -right-4">
                    <img src="/person.png" alt="Mahasiswa Universitas Muhammadiyah Gorontalo">
                </div>
			</aside>
		</main>

		<script
			type="module"
			src="https://cdn.jsdelivr.net/npm/@stisla/vanilla@3/dist/stisla.js"
		></script>
		<script src="assets/js/theme.js"></script>

		<script>
			document.addEventListener("click", function (event) {
				var toggle = event.target.closest("[data-password-toggle]");
				if (!toggle) return;
				var input = document.getElementById(toggle.getAttribute("aria-controls"));
				if (!input) return;
				var reveal = input.type === "password";
				input.type = reveal ? "text" : "password";
				toggle.setAttribute("aria-pressed", String(reveal));
			});
		</script>
        @stack('scripts')
	</body>
</html>
