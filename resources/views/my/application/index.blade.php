@extends('layouts.app')

@section('title', @$title)

@section('header')
<div class="page__headline">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb__item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb__item" aria-current="page">Pendaftaran Saya</li>
        </ol>
    </nav>
    <h1 class="page__title">Data Saya</h1>
    <p class="page__description">
        Data pendaftaran saya
    </p>
</div>
<div class="page__action">
</div>
@endsection

@section('content')
<section class="page__section">
    @include('components.alert')
    <div class="grid grid-cols-12 gap-4">
        @forelse ($data as $item)
            <div class="col-span-12 xl:col-span-4">
                <div class="card card--stat">
                    <div class="card__body">
                        <div class="card__title">
                            Pendaftaran #{{ $loop->iteration }}
                        </div>

                        <div class="mt-3">
                            <h3 class="text-lg font-semibold">
                                {{ $item->pmbRegistrationPath->name ?? 'Jalur Pendaftaran' }}
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                {{ $item->pmbWave->pmbPeriod->name ?? 'Periode PMB' }}
                            </p>
                        </div>

                        <div class="mt-4 space-y-3">
                            <div>
                                <div class="text-sm text-gray-500">
                                    Gelombang
                                </div>
                                <div class="font-medium">
                                    {{ $item->pmbWave->name ?? $item->pmbWave->code ?? '-' }}
                                </div>
                            </div>

                            <div>
                                <div class="text-sm text-gray-500">
                                    Pilihan Program Studi
                                </div>

                                @foreach ($item->studyPrograms as $sp)
                                <div class="font-medium">
                                    {{ $sp->choice_number }}. {{ $sp->studyProgram->degree_level }} {{ $sp->studyProgram->name}} ({{ $sp->studyProgram->code }})
                                </div>
                                @endforeach
                            </div>

                            <div>
                                <div class="text-sm text-gray-500">
                                    Tanggal Pendaftaran
                                </div>
                                <div class="font-medium">
                                    {{ $item->created_at?->format('d/m/Y H:i') ?? '-' }}
                                </div>
                            </div>

                            <div>
                                <div class="text-sm text-gray-500 mb-1">
                                    Tahapan Pendaftaran
                                </div>
                                {{-- Sesuaikan dengan kolom status pada model --}}
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $item->current_stage_badge['class'] }}">
                                    {{ $item->current_stage_badge['label'] }}
                                </span>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 mb-1">
                                    Status Pendaftaran
                                </div>
                                {{-- Sesuaikan dengan kolom status pada model --}}
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $item->status_badge['class'] }}">
                                    {{ $item->status_badge['label'] }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="card__footer">
                        <a href="{{ route('my.application.show', $item) }}" class="button button--lg button--neutral button--block">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-12">
                <div class="card">
                    <div class="card__body text-center py-10">
                        <h3 class="text-lg font-semibold">
                            Belum Ada Pendaftaran
                        </h3>

                        <p class="text-gray-500 mt-2">
                            Anda belum mengikuti pendaftaran mahasiswa baru.
                            Silakan pilih jalur dan gelombang pendaftaran
                            yang tersedia.
                        </p>

                        <div class="mt-4">
                            <a href="" class="button button--primary">
                                Mulai Pendaftaran
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</section>
@endsection
@push('scripts')
<script
    type="module"
    src="https://cdn.jsdelivr.net/npm/@stisla/vanilla@3/dist/stisla.js"
></script>
<script src="/assets/js/app-shell.js"></script>
<script src="/assets/js/theme.js"></script>
<script src="/assets/js/order-form.js"></script>
<script src="/assets/js/table-select.js"></script>
@endpush
