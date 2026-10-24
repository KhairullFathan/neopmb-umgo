@php
    $menu = [
        ['Beranda', route('home'), 'home.*'],
        ['Gelombang', '#gelombang_1', 'home.*'],
        ['Jalur', '#jalur_1', 'home.*'],
        ['Bantuan', '#bantuan', 'home.*'],
    ];
    if(auth()?->user()?->getRoleNames()[0] == 'camaba'){
        $menu = [
            ['Data Saya', route('my.applicant.index'), 'my.applicant.*'],
            ['Pendaftaran Saya', route('my.application.index'), 'my.application.*'],
        ];
    }
@endphp
<header class="sticky top-0 z-40 border-b border-slate-100 bg-white/95 backdrop-blur">
	<nav class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3">
		<a href="{{ route('home') }}" class="flex items-center gap-3">
			<img src="/logo.png" alt="Logo" class="h-12 w-12 object-contain">

			<span class="leading-tight">
				<span class="block text-xs font-semibold text-slate-600">Penerimaan Mahasiswa Baru</span>
				<span class="block text-base font-bold text-slate-900 sm:text-lg">Universitas Muhammadiyah Gorontalo</span>
			</span>
		</a>
		<ul class="hidden items-center gap-6 text-sm md:flex">
			@foreach ($menu as [$label, $href, $route])
				<li><a class="{{ request()->routeIs($route) ? 'font-bold text-slate-900' : 'text-slate-500' }} hover:text-brand-600" href="{{ $href }}">{{ $label }}</a></li>
			@endforeach
		</ul>
		<div class="hidden items-center gap-2 md:flex">
            @if (!(auth()->check()))
                <a href="{{ route('login') }}" class="rounded-md border border-brand-600 px-4 py-2 text-sm font-medium text-brand-600 hover:bg-brand-50">
                    Masuk
                </a>
                <a href="#gelombang_1" class="rounded-md bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700">
                    Daftar
                </a>
            @else
                @if (auth()->user()->getRoleNames()[0]=='camaba')
                <a href="{{ route('my.application.index') }}" class="rounded-md border border-brand-600 px-4 py-2 text-sm font-medium text-brand-600 hover:bg-brand-50">
                    Akun Saya
                </a>
                @else
                <a href="{{ route('dashboard') }}" class="rounded-md border border-brand-600 px-4 py-2 text-sm font-medium text-brand-600 hover:bg-brand-50">
                    Akun Saya
                </a>
                @endif
                <form action="{{ route('logout') }}" method="post" >
                    @csrf
                    @method('POST')
                    <button class="rounded-md border bg-red-600 px-3 py-1 text-center text-white">
                        Logout
                    </button>
                </form>
            @endif
		</div>
		<button id="menuBtn" aria-label="Buka menu" aria-expanded="false" class="rounded-lg p-2 md:hidden">
			<svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
		</button>
	</nav>
	<ul id="mobileMenu" class="hidden flex-col gap-1 border-t border-slate-100 px-4 py-3 text-sm font-medium md:hidden">
		@foreach ($menu as [$label, $href])
			<li><a class="block rounded-lg px-3 py-2 hover:bg-brand-50" href="{{ $href }}">{{ $label }}</a></li>
		@endforeach
        @if (!(auth()->check()))
		<li class="mt-2 grid grid-cols-2 gap-2">
			<a class="rounded-md border border-brand-600 px-3 py-2 text-center text-brand-600" href="{{ route('login') }}">
                Masuk
            </a>
			<a class="rounded-md bg-brand-600 px-3 py-2 text-center text-white" href="#daftar">
                Daftar
            </a>
		</li>
        @else
            @if (auth()->user()->getRoleNames()[0]=='camaba')
            <a href="{{ route('my.application.index') }}" class="rounded-md border border-brand-600 px-4 py-2 text-sm font-medium text-brand-600 hover:bg-brand-50">
                Akun Saya
            </a>
            @else
            <a href="{{ route('dashboard') }}" class="rounded-md border border-brand-600 px-4 py-2 text-sm font-medium text-brand-600 hover:bg-brand-50">
                Akun Saya
            </a>
            @endif
            <form action="{{ route('logout') }}" method="post" >
                @csrf
                @method('POST')
                <button class="rounded-md border bg-red-600 px-3 py-1 text-center text-white">
                    Logout
                </button>
            </form>
        @endif
	</ul>
</header>
@push('scripts')
<script>
	const mb = document.getElementById('menuBtn'), mm = document.getElementById('mobileMenu');
	function setMenu(open) { mm.classList.toggle('hidden', !open); mm.classList.toggle('flex', open); mb.setAttribute('aria-expanded', open); }
	mb.addEventListener('click', () => setMenu(mm.classList.contains('hidden')));
	mm.querySelectorAll('a').forEach(a => a.addEventListener('click', () => setMenu(false)));
</script>
@endpush
