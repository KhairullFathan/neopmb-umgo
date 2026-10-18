<!doctype html>
<html lang="en" data-theme="light">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link
				rel="icon"
				type="image/svg+xml"
				href='{{ asset('logo.png') }}'
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

		<link rel="stylesheet" href="/assets/css/style.css" />
        @stack('styles')
	</head>
	<body>
		<div class="app-shell" data-stisla-app-shell data-stisla-app-shell-auto-collapse="true">
			<aside class="sidebar sidebar--lg sidebar--app" data-stisla-sidebar>
				<header class="sidebar__header">
					<a class="sidebar__brand" href="">
						<img src="/logo.png" alt="Universitas Muhammadiyah Gorontalo">
						<span>PMB UMGO</span>
					</a>
				</header>

                <div class="sidebar__content">
                    <nav class="sidebar__menu">

                        <div class="sidebar__group">
                            <span class="sidebar__group-title">Menu</span>
                            <ul class="sidebar__list">
                                @foreach ($menus as $menu)
                                    <x-sidebar.menu-item :menu="$menu" />
                                @endforeach
                            </ul>
                        </div>

                    </nav>
                </div>

				<footer class="sidebar__footer">
					<ul class="sidebar__list">
						<li class="sidebar__item">
                            <form action="{{ route('logout') }}" method="post" class="block">
                                @csrf
                                @method('POST')
                                <button class="sidebar__button">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="1em"
                                        height="1em"
                                        viewBox="0 0 24 24"
                                        aria-hidden="true"
                                    >
                                        <path
                                            fill="currentColor"
                                            d="M16 2h-1c-2.829 0-4.242 0-5.121.879S9 5.172 9 8v8c0 2.829 0 4.243.879 5.122c.878.878 2.292.878 5.119.878H16c2.828 0 4.242 0 5.121-.879C22 20.243 22 18.828 22 16V8c0-2.828 0-4.243-.879-5.121S18.828 2 16 2"
                                            opacity=".5"
                                        />
                                        <path
                                            fill="currentColor"
                                            fill-rule="evenodd"
                                            d="M15.75 12a.75.75 0 0 0-.75-.75H4.027l1.961-1.68a.75.75 0 1 0-.976-1.14l-3.5 3a.75.75 0 0 0 0 1.14l3.5 3a.75.75 0 1 0 .976-1.14l-1.96-1.68H15a.75.75 0 0 0 .75-.75"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    Log out
                                </button>
                            </form>
						</li>
					</ul>
					<div class="copyright">
						<hr
							class="separator my-3"
							style="--separator-color: var(--sidebar-submenu-border-color)"
						/>
						<p class="text-xs text-muted-foreground" style="--link-color: var(--color-foreground)">
							Designed by <a href="https://nauv.al" class="link" target="_blank">Nauval</a> at
							<a href="https://enterprise.kredibel.com" class="link" target="_blank">Kredibel</a>
						</p>
						<p class="text-xs text-muted-foreground" style="--link-color: var(--color-foreground)">
							Develop by <a href="" class="link" target="_blank">Fathan</a> at
							<a href="" class="link" target="_blank">PMB UMGO</a>
						</p>
					</div>
				</footer>
			</aside>

			<main class="app-shell__main">
				<header class="navbar">
					<button
						type="button"
						class="button button--ghost button--neutral button--icon-only button--flush-start"
						data-stisla-app-shell-toggle="auto"
						aria-label="Toggle sidebar"
					>
						<svg
							xmlns="http://www.w3.org/2000/svg"
							width="1em"
							height="1em"
							viewBox="0 0 24 24"
							aria-hidden="true"
						>
							<path
								fill="none"
								stroke="currentColor"
								stroke-linecap="round"
								stroke-width="1.5"
								d="M20 7H4m16 5H4m16 5H4"
							/>
						</svg>
					</button>


					<div class="ms-auto">
						<div class="flex gap-1">
							{{-- dark mode toggle --}}
							<button
								type="button"
								class="button button--ghost button--neutral button--icon-only"
								data-theme-toggle
								aria-label="Toggle theme"
							>
								<svg
									xmlns="http://www.w3.org/2000/svg"
									width="1em"
									height="1em"
									viewBox="0 0 24 24"
									aria-hidden="true"
								>
									<path
										fill="currentColor"
										d="m21.067 11.857l-.642-.388zm-8.924-8.924l-.388-.642zM21.25 12A9.25 9.25 0 0 1 12 21.25v1.5c5.937 0 10.75-4.813 10.75-10.75zM12 21.25A9.25 9.25 0 0 1 2.75 12h-1.5c0 5.937 4.813 10.75 10.75 10.75zM2.75 12A9.25 9.25 0 0 1 12 2.75v-1.5C6.063 1.25 1.25 6.063 1.25 12zm12.75 2.25A5.75 5.75 0 0 1 9.75 8.5h-1.5a7.25 7.25 0 0 0 7.25 7.25zm4.925-2.781A5.75 5.75 0 0 1 15.5 14.25v1.5a7.25 7.25 0 0 0 6.21-3.505zM9.75 8.5a5.75 5.75 0 0 1 2.781-4.925l-.776-1.284A7.25 7.25 0 0 0 8.25 8.5zM12 2.75a.38.38 0 0 1-.268-.118a.3.3 0 0 1-.082-.155c-.004-.031-.002-.121.105-.186l.776 1.284c.503-.304.665-.861.606-1.299c-.062-.455-.42-1.026-1.137-1.026zm9.71 9.495c-.066.107-.156.109-.187.105a.3.3 0 0 1-.155-.082a.38.38 0 0 1-.118-.268h1.5c0-.717-.571-1.075-1.026-1.137c-.438-.059-.995.103-1.299.606z"
									/>
								</svg>
							</button>
							{{-- dark mode toggle --}}
                            @php
                                $name = auth()->user()->username;
                                if(auth()->user()->getRoleNames()[0] == 'camaba') $name = auth()->user()->applicant->name;
                            @endphp

							<div class="menu">
								<button
									type="button"
									class="button button--ghost button--neutral flex items-center gap-2"
									data-stisla-menu-trigger="topbarUser"
									aria-haspopup="menu"
									aria-expanded="false"
									aria-controls="topbarUser"
								>
									<span class="hidden sm:inline font-medium">{{ $name }} | {{ auth()->user()->getRoleNames()[0] }}</span>
									<span class="avatar avatar--sm avatar--circle" data-stisla-avatar>
                                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <circle cx="12" cy="6" r="4" stroke="#1C274C" stroke-width="1.5"></circle> <path d="M15 20.6151C14.0907 20.8619 13.0736 21 12 21C8.13401 21 5 19.2091 5 17C5 14.7909 8.13401 13 12 13C15.866 13 19 14.7909 19 17C19 17.3453 18.9234 17.6804 18.7795 18" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"></path> </g></svg>
										{{-- <span class="avatar__fallback">SG</span> --}}
									</span>
									<svg
										xmlns="http://www.w3.org/2000/svg"
										width="1em"
										height="1em"
										viewBox="0 0 24 24"
										aria-hidden="true"
									>
										<path
											fill="none"
											stroke="currentColor"
											stroke-linecap="round"
											stroke-linejoin="round"
											stroke-width="1.5"
											d="m19 9l-7 6l-7-6"
										/>
									</svg>
								</button>
								<div
									class="menu__popup w-48"
									id="topbarUser"
									data-stisla-menu
									role="menu"
									data-state="closed"
								>
									<div class="menu__group" role="group" aria-labelledby="topbarUserHead">
										<h3 class="menu__group-label" id="topbarUserHead">{{ $name }}</h3>
										<a href="/meridian/settings.html" class="menu__item" role="menuitem"
											><svg
												xmlns="http://www.w3.org/2000/svg"
												width="1em"
												height="1em"
												viewBox="0 0 24 24"
												aria-hidden="true"
											>
												<g fill="none" stroke="currentColor" stroke-width="1.5">
													<circle cx="12" cy="6" r="4" />
													<path
														d="M20 17.5c0 2.485 0 4.5-8 4.5s-8-2.015-8-4.5S7.582 13 12 13s8 2.015 8 4.5Z"
													/>
												</g>
											</svg>Profile
										</a>
									</div>
									<hr class="menu__separator" role="separator" />
                                    <form action="{{ route('logout') }}" method="post" class="inline">
                                        @csrf
                                        @method('POST')
                                        <button class="menu__item" role="menuitem">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="1em"
                                                height="1em"
                                                viewBox="0 0 24 24"
                                                aria-hidden="true"
                                            >
                                                <g
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-linecap="round"
                                                    stroke-width="1.5"
                                                >
                                                    <path
                                                        d="M9.002 7c.012-2.175.109-3.353.877-4.121C10.758 2 12.172 2 15 2h1c2.829 0 4.243 0 5.122.879C22 3.757 22 5.172 22 8v8c0 2.828 0 4.243-.878 5.121C20.242 22 18.829 22 16 22h-1c-2.828 0-4.242 0-5.121-.879c-.768-.768-.865-1.946-.877-4.121"
                                                    />
                                                    <path stroke-linejoin="round" d="M15 12H2m0 0l3.5-3M2 12l3.5 3" />
                                                </g>
                                            </svg>
                                            Log out
                                        </button>
                                    </form>
								</div>
							</div>
						</div>
					</div>
				</header>

				<div class="page content">
					<div class="content__container">
                        <header class="page__header">
                            @yield('header')
						</header>
						<div class="page__body">
                            @yield('content')
						</div>
					</div>
				</div>
			</main>
		</div>


    <!-- Third-party (CDN in both dev and prod). Loads before main.js so charts.js sees window.ApexCharts. -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3"></script>

    <script
        type="module"
        src="https://cdn.jsdelivr.net/npm/@stisla/vanilla@3/dist/stisla.js"
    ></script>
    <script src="{{ asset('assets/js/app-shell.js') }}"></script>
    <script src="{{ asset('assets/js/theme.js') }}"></script>
    <script src="{{ asset('assets/js/charts.js') }}"></script>
    <script src="{{ asset('assets/js/order-form.js') }}"></script>
    <script src="{{ asset('assets/js/table-select.js') }}"></script>
		@stack('scripts')
        <script>
            document.querySelectorAll('.currency').forEach(function (input) {
                function formatRupiah(value) {
                    // Hanya ambil angka
                    let number = value.replace(/\D/g, '');
                    if (!number) {
                        return '';
                    }
                    // Format ribuan
                    return 'Rp. ' + number.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                }
                input.addEventListener('input', function () {
                    this.value = formatRupiah(this.value);
                });
            });
        </script>
	</body>
</html>
