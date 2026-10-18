@extends('layouts.app-fe')

@section('title', $title)

@section('content')

{{-- HERO SLIDER (full lebar) --}}
<section id="slider" class="relative h-[160px] w-full overflow-hidden text-white sm:h-[560px]" aria-roledescription="carousel" aria-label="Informasi PMB">
@foreach ($slides as $i => $s)
		@php
			$adaGambar = !empty($s['gambar']) && file_exists(public_path($s['gambar']));
			$fit = ($s['fit'] ?? 'contain') === 'cover' ? 'object-cover' : 'object-contain';
		@endphp
		<div class="slide absolute inset-0 flex items-center bg-gradient-to-br {{ $s['bg'] ?? 'from-blue-700 via-brand-600 to-cyan-400' }} {{ $i > 0 ? 'opacity-0' : '' }}">
			@if ($adaGambar)
				{{-- Slide gambar: banner tampil utuh (contain) atau memenuhi area (cover) --}}
				@if (!empty($s['link']))<a href="{{ $s['link'] }}" class="block h-full w-full" tabindex="{{ $i > 0 ? '-1' : '0' }}">@endif
					<img src="{{ asset($s['gambar']) }}" alt="{{ $s['judul'] ?? 'Banner PMB' }}"
							 class="h-full w-full {{ $fit }} {{ $fit === 'object-contain' ? 'px-4 py-6 sm:px-10 sm:py-8' : '' }}"
							 @if ($i > 0) loading="lazy" @endif>
				@if (!empty($s['link']))</a>@endif
			@elseif (!empty($s['judul']))
				{{-- Slide teks (fallback jika gambar belum ada) --}}
				<div class="absolute -right-20 -top-20 h-80 w-80 rounded-full bg-white/15"></div>
				<div class="absolute bottom-8 right-32 h-24 w-24 rounded-full border-4 border-white/30"></div>
				<div class="relative mx-auto w-full max-w-6xl px-6 sm:px-12">
					<div class="max-w-xl rounded-2xl bg-white/95 p-6 text-slate-800 shadow-xl sm:p-8">
						<p class="text-sm font-semibold text-brand-600">{{ $s['label'] ?? '' }}</p>
						<h2 class="mt-1 text-2xl font-bold leading-tight text-slate-900 sm:text-4xl">{{ $s['judul'] }}</h2>
						<p class="mt-3 text-slate-600">{{ $s['teks'] ?? '' }}</p>
					</div>
				</div>
			@endif
		</div>
	@endforeach
	<button id="prev" aria-label="Slide sebelumnya" class="absolute left-3 top-1/2 -translate-y-1/2 rounded-full bg-white/80 p-2 text-brand-700 hover:bg-white">
		<svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M13 4l-6 6 6 6"/></svg>
	</button>
	<button id="next" aria-label="Slide berikutnya" class="absolute right-3 top-1/2 -translate-y-1/2 rounded-full bg-white/80 p-2 text-brand-700 hover:bg-white">
		<svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M7 4l6 6-6 6"/></svg>
	</button>
	<div id="dots" class="absolute bottom-4 left-1/2 flex -translate-x-1/2 gap-2"></div>
</section>

{{-- CTA --}}
<section id="daftar" class="border-b border-brand-100 bg-brand-50">
	<div class="mx-auto flex max-w-5xl flex-col items-start justify-between gap-4 px-4 py-8 sm:flex-row sm:items-center">
		<div>
			<p class="text-sm font-medium text-slate-600">Universitas Muhammadiyah Gorontalo</p>
			<h1 class="mt-0.5 text-xl font-bold uppercase text-brand-800 sm:text-2xl">Membuka Penerimaan Mahasiswa Baru</h1>
			<p class="mt-1 text-sm text-slate-600">Gabung bersama kami. Pendaftaran hanya butuh sekitar 10 menit.</p>
		</div>
		<a href="{{ url('/daftar') }}" class="w-full rounded-md bg-brand-600 px-8 py-3 text-center text-sm font-semibold uppercase text-white shadow-sm hover:bg-brand-700 sm:w-auto">Daftar Sekarang</a>
	</div>
</section>

{{-- GELOMBANG --}}
@include('partials.wave')

{{-- FAQ --}}
<section id="faq" class="bg-white py-14">
	<div class="mx-auto max-w-3xl px-4">
		<h2 class="text-center text-2xl font-bold text-slate-900">Pertanyaan yang Sering Diajukan</h2>
		<p class="mt-1 text-center text-sm text-slate-500">Jawaban singkat seputar pendaftaran mahasiswa baru</p>

		<div class="mt-8 space-y-3">
			@foreach ($faq as $f)
				<details class="group rounded-xl border border-brand-100 bg-white open:border-brand-200 open:bg-brand-50" @if ($loop->first) open @endif>
					<summary class="flex cursor-pointer list-none items-center justify-between gap-4 rounded-xl px-5 py-4 font-semibold text-slate-900 marker:content-none hover:text-brand-700 [&::-webkit-details-marker]:hidden">
						<span>{{ $f['tanya'] }}</span>
						<svg class="h-5 w-5 shrink-0 text-brand-600 transition-transform group-open:rotate-180" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 8l5 5 5-5"/></svg>
					</summary>
					<div class="px-5 pb-5 text-sm leading-relaxed text-slate-600">{{ $f['jawab'] }}</div>
				</details>
			@endforeach
		</div>

		<p class="mt-8 text-center text-sm text-slate-600">
			Belum menemukan jawabannya?
			<a href="https://wa.me/{{ config('pmb.wa_bot') }}" target="_blank" rel="noopener" class="font-semibold text-brand-600 hover:underline">Tanya WA Bot</a>.
		</p>
	</div>
</section>

{{-- LAYANAN BANTUAN --}}
<section id="bantuan" class="bg-brand-600 py-12 text-white">
	<div class="mx-auto grid max-w-5xl gap-6 px-4 md:grid-cols-[auto_1fr]">
		<svg class="hidden h-20 w-20 md:block" viewBox="0 0 24 24" fill="currentColor"><path d="M4 4h12a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H9l-4 3v-3H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2zm16 5h-1v4a3 3 0 0 1-3 3h-5v1a2 2 0 0 0 2 2h4l3 2v-2a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2z" opacity=".9"/></svg>
		<div>
			<h2 class="text-2xl font-semibold">Mengalami Kendala dalam Proses Pendaftaran?</h2>
			<p class="mt-1 text-brand-100">Jika Anda mengalami kendala saat membuat akun, mengisi formulir pendaftaran, mengunggah berkas, melakukan pembayaran, atau proses pendaftaran lainnya, silakan hubungi Bot WhatsApp PMB UMGO untuk mendapatkan informasi dan panduan lebih lanjut.</p>
			<p class="mt-1 text-brand-300">Klik tombol di bawah ini untuk memulai percakapan melalui WhatsApp.</p>
            <div class="mt-5">
                <a
                    href="https://wa.me/{{ config('pmb.wabot_number') }}?text={{ rawurlencode('Halo, saya ingin bertanya tentang PMB') }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 rounded-lg bg-white px-5 py-3 font-semibold text-brand-700 shadow-sm transition hover:bg-brand-50 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M20.52 3.48A11.87 11.87 0 0 0 12.05 0C5.48 0 .13 5.34.12 11.91c0 2.1.55 4.15 1.6 5.96L0 24l6.28-1.65a11.9 11.9 0 0 0 5.76 1.47h.01c6.57 0 11.92-5.34 11.93-11.91a11.84 11.84 0 0 0-3.46-8.43ZM12.05 21.8a9.9 9.9 0 0 1-5.04-1.38l-.36-.21-3.73.98.99-3.64-.24-.37a9.85 9.85 0 0 1-1.52-5.27c0-5.47 4.45-9.91 9.92-9.91a9.84 9.84 0 0 1 7.01 2.9 9.84 9.84 0 0 1 2.9 7.02c0 5.47-4.45 9.91-9.93 9.91Zm5.45-7.42c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.25-.46-2.38-1.47-.88-.78-1.47-1.75-1.64-2.05-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.07-.15-.67-1.62-.92-2.22-.24-.58-.48-.5-.67-.51h-.57c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.49s1.07 2.89 1.22 3.09c.15.2 2.1 3.2 5.08 4.49.71.31 1.27.5 1.7.64.71.23 1.36.2 1.87.12.57-.08 1.76-.72 2.01-1.42.25-.7.25-1.3.17-1.42-.07-.12-.27-.2-.57-.35Z"/>
                    </svg>
                    Hubungi Bot WhatsApp PMB
                </a>
                <p class="mt-2 text-sm text-brand-100">
                    Layanan informasi dan panduan pendaftaran mahasiswa baru.
                </p>
            </div>
		</div>
	</div>
</section>

{{-- PROGRAM STUDI --}}
<section id="prodi" class="bg-white py-14">
	<div class="mx-auto max-w-6xl px-4">
		<h2 class="text-center text-2xl font-bold text-slate-900">Program Studi yang Dibuka</h2>
		<div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
			@foreach ($prodi as $p)
				<div class="rounded-xl border border-brand-100 p-5">
					<p class="font-semibold text-slate-900">{{ $p['nama'] }}</p>
					<p class="mt-1 text-sm text-slate-500">{{ $p['info'] }}</p>
				</div>
			@endforeach
		</div>
	</div>
</section>

@endsection

@push('scripts')
<script>
	// Slider
	const slides = [...document.querySelectorAll('.slide')], dots = document.getElementById('dots');
	let cur = 0, timer;
	slides.forEach((_, i) => {
		const d = document.createElement('button');
		d.setAttribute('aria-label', 'Ke slide ' + (i + 1));
		d.className = 'h-2.5 w-2.5 rounded-full bg-white/50 transition-all';
		d.onclick = () => { go(i); restart(); };
		dots.appendChild(d);
	});
	function go(n) {
		cur = (n + slides.length) % slides.length;
		slides.forEach((s, i) => { s.style.opacity = i === cur ? 1 : 0; s.style.pointerEvents = i === cur ? 'auto' : 'none'; s.setAttribute('aria-hidden', i !== cur); });
		[...dots.children].forEach((d, i) => { d.classList.toggle('bg-white', i === cur); d.classList.toggle('w-6', i === cur); d.classList.toggle('bg-white/50', i !== cur); });
	}
	function restart() {
		clearInterval(timer);
		if (!matchMedia('(prefers-reduced-motion: reduce)').matches) timer = setInterval(() => go(cur + 1), 5500);
	}
	document.getElementById('prev').onclick = () => { go(cur - 1); restart(); };
	document.getElementById('next').onclick = () => { go(cur + 1); restart(); };
	go(0); restart();
</script>
@endpush
