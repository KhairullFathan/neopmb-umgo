@php
    $periods = \App\Models\PmbPeriod::where('is_active', true)->get();
    $now = now();
@endphp
@foreach ($periods as $item)
@php
    $waves = $item->pmbWave()->orderBy('code','ASC')->get();
    $wave_on_going = $item->pmbWave()
        ->where('registration_start', '<=' ,$now)
        ->where('registration_end', '>=' ,$now)
        ->orderBy('code', 'ASC')
        ->first();

@endphp
@if ($wave_on_going)
<section id="gelombang_{{ $loop->iteration }}" class="bg-white py-14">
	<div class="mx-auto max-w-6xl px-4">
		<h2 class="text-center text-2xl font-bold text-slate-900">
            Gelombang Pendaftaran
            <br>
            Periode {{ $item->name }} ({{ $item->id }})
        </h2>
		<p class="mt-1 text-center text-sm text-slate-500">Gelombang yang saat ini sedang berlangsung</p>

		<div class="mt-8 grid gap-6 lg:grid-cols-5">
			<article class="order-1 rounded-2xl bg-brand-600 p-6 text-white shadow-md sm:p-8 lg:col-span-3 lg:row-start-1 lg:order-none {{ $loop->even ? 'lg:col-start-3' : 'lg:col-start-1' }}">
				<div class="flex items-center gap-2 text-sm font-semibold">
					<span class="relative flex h-3 w-3"><span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-white opacity-75"></span><span class="relative inline-flex h-3 w-3 rounded-full bg-white"></span></span>
					Sedang dibuka
				</div>
				<h3 class="mt-2 text-3xl font-bold sm:text-4xl">{{ $wave_on_going->name }} ({{ $wave_on_going->code }})</h3>
				<p class="mt-1 text-brand-100">
                    {{ \Carbon\Carbon::parse($wave_on_going->registration_start)->format('d M Y') }} - {{ \Carbon\Carbon::parse($wave_on_going->registration_end)->format('d M Y') }}
                </p>

				<dl class="mt-6 grid grid-cols-2 gap-4 text-sm sm:grid-cols-3">
					<div><dt class="text-brand-100">Biaya pendaftaran</dt><dd class="mt-0.5 text-lg font-semibold">-</dd></div>
					<div><dt class="text-brand-100">Tes seleksi</dt><dd class="mt-0.5 text-lg font-semibold">-</dd></div>
					<div><dt class="text-brand-100">Pengumuman</dt><dd class="mt-0.5 text-lg font-semibold">-</dd></div>
				</dl>

				{{-- @php $persen = round($gelombang['terisi'] / $gelombang['kuota'] * 100); @endphp
				<div class="mt-6">
					<div class="flex justify-between text-sm"><span>Kuota terisi</span><span class="font-semibold">{{ $gelombang['terisi'] }} dari {{ $gelombang['kuota'] }}</span></div>
					<div class="mt-2 h-2.5 rounded-full bg-white/25" role="progressbar" aria-valuenow="{{ $persen }}" aria-valuemin="0" aria-valuemax="100">
						<div class="h-full rounded-full bg-white" style="width: {{ $persen }}%"></div>
					</div>
				</div> --}}

				<div class="mt-6 flex flex-wrap items-center gap-4">
					<a href="#jalur_{{ $loop->iteration }}" class="rounded-md bg-white px-6 py-3 text-sm font-semibold text-brand-700 hover:bg-brand-50">
                        Daftar di {{ $wave_on_going->name }} {{ $item->id }}
                    </a>
					<p class="text-sm text-brand-100">Ditutup dalam <span data-end="{{ $wave_on_going->registration_end }}" class="countdown font-semibold text-white">–</span></p>
				</div>
			</article>

			<ol class="order-2 space-y-3 lg:col-span-2 lg:order-none {{ $loop->even ? 'lg:col-start-1' : 'lg:col-start-4' }}">
                @php
                    $lastCount = 0;
                @endphp
				@foreach ($waves as $wave)
					@php
                        if($wave->registration_end < $now){
                            $badge = 'bg-slate-100 text-slate-500';
                        }
                        $lastCount = $loop->iteration;
					@endphp
					<li class="flex items-center gap-4 rounded-xl p-4 {{ $wave->is_active ? 'border-2 border-brand-600 bg-brand-50' : 'border border-slate-200 bg-white' }} {{ ($wave->registration_end < $now) === 'selesai' ? 'opacity-70' : '' }}">
						<span class="grid h-10 w-10 shrink-0 place-items-center rounded-full font-bold {{ $wave->is_active ? 'bg-brand-600 text-white' : 'bg-brand-100 text-brand-700' }}">{{ $loop->iteration }}</span>
						<div class="flex-1">
							<p class="font-semibold {{ $wave->is_active ? 'text-brand-800' : 'text-slate-800' }}">{{ $wave->name }}</p>
							<p class="text-sm text-slate-500">
                                {{ \Carbon\Carbon::parse($wave->registration_start)->format('d M Y') }}-{{ \Carbon\Carbon::parse($wave->registration_end)->format('d M Y') }}
                            </p>
						</div>
                        @if ($wave->registration_end < $now)
						<span class="rounded-full px-3 py-1 text-xs font-semibold bg-slate-100 text-slate-500">Selesai</span>
                        @elseif ($wave->registration_start > $now)
						<span class="rounded-full px-3 py-1 text-xs font-semibold bg-sky-100 text-sky-700">Segera</span>
                        @else
						<span class="rounded-full px-3 py-1 text-xs font-semibold bg-emerald-100 text-emerald-700">Dibuka</span>
                        @endif
					</li>
				@endforeach
                {{-- skeleton --}}
                <li class="flex items-center gap-4 rounded-xl p-4 border border-slate-200 bg-white">
                    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full font-bold bg-brand-100 text-brand-700">{{ ++$lastCount }}</span>
                    <div class="flex-1">
                        <p class="font-semibold text-slate-800">Dijadwalkan</p>
                        <p class="text-sm text-slate-500"></p>
                    </div>
                    <span class="rounded-full px-3 py-1 text-xs font-semibold bg-sky-100 text-sky-700">Segera</span>
                </li>
			</ol>
		</div>
	</div>
</section>
{{-- JALUR PENDAFTARAN --}}
@php
    $paths = $wave_on_going->pmbWavePath()->where('is_active',true)->get();
@endphp
<section id="jalur_{{ $loop->iteration }}" class="bg-brand-50 py-14">
	<div class="mx-auto max-w-6xl px-4">
		<h2 class="text-center text-2xl font-bold text-slate-900">
            Jalur Pendaftaran
            <br>
            {{$wave_on_going->name}}
            <br>
            Periode {{ $item->name }} ({{ $item->id }})
        </h2>
		<p class="mt-1 text-center text-sm text-slate-500">Daftar jalur pendaftaran yang buka pada gelombang ini.</p>

		<div class="mt-8 grid gap-6 grid-cols-1 xl:grid-cols-3">
			@foreach ($paths as $path)
			<article class="flex flex-col overflow-hidden rounded-xl rounded-tl-[2.5rem] bg-white shadow-md ring-1 ring-brand-100">
				<div class="rounded-br-[2.5rem] bg-brand-600 px-6 pb-8 pt-7 text-center text-white">
                    <span class="inline-block rounded-full bg-white/20 px-3 py-0.5 text-xs font-semibold">Dibuka</span>
                    <h3 class="mt-2 text-lg font-semibold">{{ $path->pmbRegistrationPath->name }}</h3>
                    <p class="mt-2 flex items-center justify-center gap-1.5 text-sm">
                        <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="8" cy="8" r="6.5"/><path d="M8 4.5V8l2.5 1.5"/></svg>{{ \Carbon\Carbon::parse($wave_on_going->registration_start)->format('d M Y') }}
                    </p>
                    <p class="text-sm text-brand-100">s/d</p>
                    <p class="flex items-center justify-center gap-1.5 text-sm">
                        <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="8" cy="8" r="6.5"/><path d="M8 4.5V8l2.5 1.5"/></svg>{{ \Carbon\Carbon::parse($wave_on_going->registration_end)->format('d M Y') }}
                    </p>
				</div>

				<dl class="grid grid-cols-2 gap-x-3 gap-y-4 px-5 py-5 text-sm">
                    <div class="col-span-2 text-center"><dt class="text-xs text-slate-500">Periode</dt><dd class="font-semibold text-slate-800">{{ $item->name }}</dd></div>
                    <div><dt class="text-xs text-slate-500">Gelombang</dt><dd class="font-semibold text-slate-800">{{ $wave_on_going->name }}</dd></div>
                    <div><dt class="text-xs text-slate-500">Formulir</dt><dd class="font-semibold text-slate-800">{{ $path->registration_fee_formatted }}</dd></div>
				</dl>

				<div class="mt-auto grid grid-cols-2 gap-2 px-5 pb-5">
                    <a href="{{ route('home.wave.path.show', $path) }}" class="rounded-md bg-orange-500 px-3 py-2 text-center text-sm font-medium text-white hover:bg-orange-600">Detail</a>
                    <a href="{{ route('home.registration', $path) }}" class="rounded-md bg-brand-600 px-3 py-2 text-center text-sm font-medium text-white hover:bg-brand-700">Daftar</a>
				</div>
			</article>
			@endforeach
		</div>
	</div>
</section>
@endif
@endforeach

{{-- @push('scripts') --}}
<script>
// Hitung mundur penutupan gelombang aktif
document.querySelectorAll('.countdown').forEach(cd => {
    const end = new Date(cd.dataset.end);
    const ms = end - Date.now();
    if (ms <= 0) {
        cd.textContent = 'Sudah ditutup';
        return;
    }
    cd.textContent =
        Math.floor(ms / 864e5) + ' hari ' +
        Math.floor(ms % 864e5 / 36e5) + ' jam';
});
</script>
{{-- @endpush --}}
