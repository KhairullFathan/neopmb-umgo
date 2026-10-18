@extends('layouts.app-fe')

@section('title', $title)

@section('content')

{{-- Header --}}
<section class="bg-gradient-to-br from-blue-700 via-brand-600 to-cyan-400 text-white">
	<div class="mx-auto max-w-6xl px-4 py-10 sm:py-14">
		<nav aria-label="Breadcrumb" class="text-sm text-brand-100">
			<a href="{{ route('home') }}" class="hover:underline">Beranda</a>
			<span class="mx-1">/</span>
			<a href="#" class="hover:underline">Jalur Pendaftaran</a>
		</nav>
		<span class="mt-4 inline-block rounded-full bg-white/20 px-3 py-0.5 text-xs font-semibold">Dibuka</span>
		<h1 class="mt-2 text-3xl font-bold sm:text-4xl">{{ $data->pmbRegistrationPath->name }}</h1>
		<p class="mt-2 max-w-2xl text-brand-100">{{ $data->pmbRegistrationPath->description }}</p>
		<p class="mt-4 text-sm font-medium">{{ \Carbon\Carbon::parse($data->pmbWave->registration_start)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($data->pmbWave->registration_end)->format('d M Y') }}</p>
	</div>
</section>

<section class="bg-white py-12">
	<div class="mx-auto grid max-w-6xl gap-8 px-4 lg:grid-cols-3">

		{{-- Konten utama --}}
		<div class="space-y-10 lg:col-span-2">

			<div>
				<h2 class="text-xl font-bold text-slate-900">Informasi umum</h2>
				<dl class="mt-4 grid grid-cols-1 overflow-hidden rounded-xl border border-brand-100">
					@foreach ([
						'Periode' => $data->pmbWave->pmbPeriod->name,
						'Gelombang' => $data->pmbWave->name,
						'Biaya formulir' => $data->registration_fee_formatted,
						// 'Seleksi' => $jalur['tes'],
						// 'Pengumuman' => $jalur['pengumuman'],
					] as $label => $nilai)
						<div class="border-b border-brand-100 p-4 sm:odd:border-r">
							<dt class="text-xs text-slate-500">{{ $label }}</dt>
							<dd class="mt-0.5 font-semibold text-slate-800">{{ $nilai }}</dd>
						</div>
					@endforeach
				</dl>
			</div>

			<div>
				<h2 class="text-xl font-bold text-slate-900">Berkas yang perlu disiapkan</h2>
				<ul class="mt-4 grid gap-2 sm:grid-cols-2">
					@foreach ($data->pmbRegistrationPath->pmbRegistrationDocument as $item)
						<li class="rounded-lg bg-brand-50 px-4 py-3 text-sm text-slate-700">{{ $item->name }} <sup class="text-red-500">{{ $item->is_required?'Wajib':'' }}</sup></li>
					@endforeach
				</ul>
			</div>

			<div>
				<h2 class="text-xl font-bold text-slate-900">Tahapan Pendaftaran</h2>
				<ol class="mt-4 space-y-3">
                    @php $count=0 @endphp
					@foreach ($data->stages as $item)
                        @php $count++; @endphp
						<li class="flex items-center gap-4 rounded-xl border border-brand-100 p-4">
							<span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-brand-600 text-sm font-bold text-white">{{ $loop->iteration }}</span>
							<span class="font-medium text-slate-800">{{ $item->pmbRegistrationStage->name }}</span>
						</li>
                    @endforeach
                    <li class="flex items-center gap-4 rounded-xl border border-brand-100 p-4">
                        <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-brand-600 text-sm font-bold text-white">{{ ++$count }}</span>
                        <span class="font-medium text-slate-800">Menjadi Mahasiswa UMGO</span>
                    </li>
				</ol>
			</div>

			<div>
				<h2 class="text-xl font-bold text-slate-900">{{ $data->studyPrograms->count() }} Program studi yang tersedia</h2>
				<div class="mt-4 flex flex-wrap gap-2">
					@foreach ($data->studyPrograms as $p)
						<span class="rounded-full border border-brand-200 bg-white px-4 py-1.5 text-sm font-medium text-brand-700">{{ $p->degree_level }} {{ $p->name }}</span>
					@endforeach
				</div>
			</div>
		</div>

		{{-- Sidebar --}}
		<aside class="lg:sticky lg:top-24 lg:self-start">
			<div class="overflow-hidden rounded-xl rounded-tl-[2.5rem] bg-white shadow-md ring-1 ring-brand-100">
				<div class="rounded-br-[2.5rem] bg-brand-600 px-6 pb-8 pt-7 text-white">
					<p class="text-sm text-brand-100">Biaya formulir</p>
					<p class="text-2xl font-bold">{{ $data->registration_fee_formatted }}</p>
					<p class="mt-2 text-sm text-brand-100">Ditutup {{ \Carbon\Carbon::parse($data->pmbWave->registration_end)->format('d M Y') }}</p>
				</div>
				<div class="space-y-2 p-5">
					<a href="" class="block rounded-md bg-brand-600 px-4 py-3 text-center text-sm font-semibold text-white hover:bg-brand-700">Daftar {{ $data->pmbRegistrationPath->name }}</a>
					<a href="https://wa.me/{{ config('pmb.wabot_number') }}" target="_blank" rel="noopener" class="block rounded-md border border-brand-600 px-4 py-3 text-center text-sm font-semibold text-brand-600 hover:bg-brand-50">Tanya WA Bot</a>
				</div>
			</div>

			<div class="mt-6 rounded-xl border border-brand-100 p-5">
				<p class="text-sm font-semibold text-slate-900">Jalur lainnya</p>
				<ul class="mt-3 space-y-2 text-sm">
					@foreach ($other as $item)
						<li><a href="{{ route('home.wave.path.show', $item) }}" class="flex items-center justify-between rounded-lg px-3 py-2 text-slate-700 hover:bg-brand-50 hover:text-brand-700"><span>{{ $item->pmbRegistrationPath->name }}</span><span aria-hidden="true">›</span></a></li>
					@endforeach
				</ul>
			</div>
		</aside>
	</div>
</section>
@endsection
