@extends('layouts.app-fe')

@section('title', @$title)

@section('content')

{{-- Header --}}
<section class="fe-hero">
    <div class="fe-hero__inner">
        <nav aria-label="Breadcrumb" class="fe-hero__crumb">
            <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a>
            <span class="mx-1">/</span>
            <span aria-current="page">Pendaftaran Saya</span>
        </nav>
        <h1 class="fe-hero__title">Pendaftaran Saya</h1>
        <p class="fe-hero__desc">Pantau jalur, gelombang, tahapan, dan status pendaftaran mahasiswa baru Anda.</p>
    </div>
</section>

<section class="bg-white py-10">
    <div class="mx-auto max-w-6xl px-4">

        @include('components.fe.alert')

        <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
            @forelse ($data as $item)
                <article class="fe-card flex h-full flex-col transition hover:-translate-y-0.5 hover:shadow-md">

                    {{-- Kepala kartu --}}
                    <div class="bg-gradient-to-br from-blue-700 via-brand-600 to-cyan-400 px-6 pb-6 pt-5 text-white">
                        <span class="inline-block rounded-full bg-white/20 px-3 py-0.5 text-xs font-semibold">
                            Pendaftaran #{{ $loop->iteration }}
                        </span>
                        <h3 class="mt-3 text-lg font-bold leading-snug">
                            {{ $item->pmbRegistrationPath->name ?? 'Jalur Pendaftaran' }}
                        </h3>
                        <p class="mt-1 text-sm text-brand-100">
                            {{ $item->pmbWave->pmbPeriod->name ?? 'Periode PMB' }}
                        </p>
                    </div>

                    {{-- Isi kartu --}}
                    <div class="flex-1 space-y-5 p-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="fe-meta__label">Gelombang</div>
                                <div class="fe-meta__value">
                                    {{ $item->pmbWave->name ?? $item->pmbWave->code ?? '-' }}
                                </div>
                            </div>
                            <div>
                                <div class="fe-meta__label">Tanggal Daftar</div>
                                <div class="fe-meta__value">
                                    {{ $item->created_at?->format('d/m/Y H:i') ?? '-' }}
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="fe-meta__label">Pilihan Program Studi</div>
                            <ul class="mt-2 space-y-2">
                                @foreach ($item->studyPrograms as $sp)
                                <li class="flex items-start gap-3 rounded-lg bg-brand-50 px-3 py-2 text-sm text-slate-700">
                                    <span class="grid h-5 w-5 shrink-0 place-items-center rounded-full bg-brand-600 text-xs font-bold text-white">{{ $sp->choice_number }}</span>
                                    <span class="font-medium">{{ $sp->studyProgram->degree_level }} {{ $sp->studyProgram->name}} ({{ $sp->studyProgram->code }})</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="grid grid-cols-1 gap-4 border-t border-brand-100 pt-4 sm:grid-cols-2">
                            <div>
                                <div class="fe-meta__label mb-1.5">Tahapan</div>
                                {{-- Sesuaikan dengan kolom status pada model --}}
                                <span class="fe-badge {{ $item->current_stage_badge['class'] }}">
                                    {{ $item->current_stage_badge['label'] }}
                                </span>
                            </div>
                            <div>
                                <div class="fe-meta__label mb-1.5">Status</div>
                                {{-- Sesuaikan dengan kolom status pada model --}}
                                <span class="fe-badge {{ $item->status_badge['class'] }}">
                                    {{ $item->status_badge['label'] }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Footer kartu --}}
                    <div class="border-t border-brand-100 p-5">
                        <a href="{{ route('my.application.show', $item) }}" class="fe-btn fe-btn--primary fe-btn--block">
                            Lihat Detail
                        </a>
                    </div>
                </article>
            @empty
                <div class="sm:col-span-2 xl:col-span-3">
                    <div class="rounded-xl border-2 border-dashed border-brand-200 bg-brand-50/40 px-6 py-14 text-center">
                        <span class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-brand-100 text-2xl text-brand-600" aria-hidden="true">📝</span>
                        <h3 class="mt-4 text-lg font-bold text-slate-900">
                            Belum Ada Pendaftaran
                        </h3>

                        <p class="mx-auto mt-2 max-w-md text-slate-600">
                            Anda belum mengikuti pendaftaran mahasiswa baru.
                            Silakan pilih jalur dan gelombang pendaftaran
                            yang tersedia.
                        </p>

                        <div class="mt-6">
                            <a href="" class="fe-btn fe-btn--primary">
                                Mulai Pendaftaran
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
