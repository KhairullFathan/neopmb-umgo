@extends('layouts.app')

@section('title', @$title)

@section('header')
<div class="page__headline">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb__item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb__item" aria-current="page">Formulir PMB</li>
        </ol>
    </nav>
    <h1 class="page__title">Data Formulir PMB</h1>
    <p class="page__description">
        Daftar formulir pendaftaran mahasiswa baru di Universitas Muhammadiyah Gorontalo
    </p>
</div>
<div class="page__action">
    <a href="{{ route('pmb.application.create') }}" class="button button--primary">
        <x-icon icon="plus" />
        Tambah
    </a>
</div>
@endsection

@section('content')
<section class="page__section">
    @include('components.alert')
    <div class="card">
        <form action="" method="get">
            <div class="card__header flex-wrap">
                <h4>Filter</h4>
            </div>
            <div class="card__body">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div class="field">
                        <label for="search" class="field__label">Kata Kunci (NIK/Nama)</label>
                        <input type="text" class="input" id="search" name="search" placeholder="johndoe" value="{{ request('search') }}"/>
                    </div>
                    <div class="field">
                        <label for="search" class="field__label">Periode</label>
                        <select name="period_id" id="period_id" class="select">
                            <option value="">Pilih Opsi</option>
                            @foreach ($periods as $item=>$name)
                            <option value="{{ $item }}" {{ request('period_id') == $item ? 'selected':'' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if (request()->has('period_id') && request()->period_id != '')
                    <div class="field">
                        <label for="search" class="field__label">Gelombang</label>
                        <select name="wave_id" id="wave_id" class="select">
                            <option value="">Pilih Opsi</option>
                            @foreach ($pmbWaves as $item)
                            <option value="{{ $item->id }}" {{ request('wave_id') == $item->id ? 'selected':'' }}>{{ $item->pmbPeriod->name }} {{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    @if (request()->has('wave_id') && request()->wave_id != '')
                    <div class="field">
                        <label for="search" class="field__label">Jalur</label>
                        <select name="registration_path_id" id="registration_path_id" class="select">
                            <option value="">Pilih Opsi</option>
                            @foreach ($pmbWavePaths as $item)
                            <option value="{{ $item->pmbRegistrationPath->id }}" {{ request('registration_path_id') == $item->pmbRegistrationPath->id ? 'selected':'' }}>({{ $item->pmbRegistrationPath->code }}) {{ $item->pmbRegistrationPath->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>
            </div>
            <div class="card__footer">
                <button type="submit" class="button button--primary">
                    <x-icon icon="search" />
                    Cari
                </button>
                <a href="{{ url()->current() }}" class="button button--danger">
                    <x-icon icon="times" />
                    Reset
                </a>
            </div>
        </form>
    </div>
    <div class="card">
        <div class="card__header flex-wrap">
            <div>{{ $data->total() }} Formulir</div>
        </div>

        <div class="table-responsive">
            <table class="table table--hover table--align-middle">
                <thead class="table__head--alt">
                    <tr>
                        <th scope="col">No Formulir</th>
                        <th scope="col">Formulir</th>
                        <th scope="col">Berkas<br/>Masuk</th>
                        <th scope="col" class="text-center">No. Hp.<br>No. Hp. Ortu</th>
                        <th scope="col" class="text-center">Tahapan<br>Saat ini</th>
                        <th scope="col" class="text-end"><span class="sr-only">Aksi</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $row)
                    @php
                        $pmbWavePath = $row->pmbWavePath()->first();
                        $stages = $pmbWavePath->stages()
                            ->where('is_active', true)
                            ->with('pmbRegistrationStage')
                            ->orderBy('sequence')
                            ->get();
                        $payment_stage = $stages->first(
                            fn($stage) => $stage->pmbRegistrationStage->code === 'PEMBAYARAN_FORMULIR'
                        );
                    @endphp
                    <tr>
                        <td scope="row">{{ $row->registration_number }}</td>
                        <td scope="row">
                            <div class="text-lg font-bold">{{ $row->applicant->name }}</div>
                            <div class="">{{ $row->applicant->identity_number??'-' }}</div>
                            <div class="">{{ $row->pmbWave->pmbPeriod->name }} {{ $row->pmbWave->name }} {{ $row->pmbRegistrationPath->name }}</div>
                            <div class="mt-2">Pilihan Prodi:</div>
                            @foreach ($row->StudyPrograms as $study_program)
                            <div class="font-bold">{{ $study_program->choice_number }}. ({{ $study_program->studyProgram->degree_level }}) {{ $study_program->studyProgram->name }}</div>
                            @endforeach
                            <div class="mt-2">Terdaftar: {{ $row->created_at->diffForHumans() }}</div>
                        </td>
                        <td scope="row" class="">
                            @if ($payment_stage)
                            @php
                                $payment = $row->payments()->where(
                                    'payment_type','formulir'
                                )->first();
                                $pay_status = match ($payment->verification_status ?? null) {
                                    'approved' => [
                                        'label'=> 'Diterima',
                                        'class'=> 'bg-[#ccfbf1] text-[#115e59]',
                                    ],
                                    'rejected' => [
                                        'label' => 'Ditolak',
                                        'class'=> 'bg-[#fce7f3] text-[#9d174d]',
                                    ],
                                    'pending'  => [
                                        'label' => 'Menunggu verifikasi',
                                        'class' => 'bg-[#dbeafe] text-[#1e40af]'
                                    ],
                                    'not_required'  => [
                                        'label' => 'Tidak Wajib',
                                        'class' => 'bg-[#ccfbf1] text-[#115e59]'
                                    ],
                                    default => [
                                        'label' => 'Belum ada',
                                        'class' => 'bg-[#f3e8ff] text-[#6b21a8]'
                                    ]
                                };
                            @endphp
                            <div>Formulir Pendaftaran <sup class="badge {{ $pay_status['class'] }}">{{ $pay_status['label'] }}</sup></div>
                            @endif
                            @foreach ($row->pmbRegistrationPath->pmbRegistrationDocument as $req)
                            @php
                                $doc = $row->documents->firstWhere('pmb_registration_document_id', $req->id);
                                $doc_status = match ($doc->verification_status ?? null) {
                                    'approved' => [
                                        'label'=> 'Diterima',
                                        'class'=> 'bg-[#ccfbf1] text-[#115e59]',
                                    ],
                                    'rejected' => [
                                        'label' => 'Ditolak',
                                        'class'=> 'bg-[#fce7f3] text-[#9d174d]',
                                    ],
                                    'pending'  => [
                                        'label' => 'Menunggu verifikasi',
                                        'class' => 'bg-[#dbeafe] text-[#1e40af]'
                                    ],
                                    default => [
                                        'label' => 'Belum ada',
                                        'class' => 'bg-[#f3e8ff] text-[#6b21a8]'
                                    ]
                                };
                            @endphp
                            <div>{{ $req->name }} <sup class="badge {{ $doc_status['class'] }}">{{ $doc_status['label'] }}</sup></div>
                            @endforeach
                        </td>
                        <td scope="row" class="text-center">{{ $row->applicant->phone }}<br>{{ $row->applicant->parent_phone??'-' }}</td>
                        <td scope="row" class="text-center">
                            <span class="badge {{ $row->currentStageBadge['class'] }}">{{ $row->currentStageBadge['label'] }}</span>
                        </td>
                        <td scope="row" class="text-end">
                            <div class="flex justify-end gap-1">
                                <a
                                    href="{{ route('pmb.application.show', $row) }}"
                                    class="button button--ghost button--primary button--icon-only button--sm"
                                    aria-label="Detail"
                                >
                                    <x-icon icon="eye"/>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-folder-open text-4xl mb-3 block"></i>
                            <span class="text-sm font-medium">Tidak ada data {{ strtolower(@$title) }} yang ditemukan.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card__footer">
            <x-pagination :paginator="$data"></x-pagination>
        </div>
    </div>
</section>
<x-delete-dialog
    id="deleteDialog"
    title="Hapus Gelombang"
    description="Data gelombang PMB yang dihapus tidak dapat dikembalikan."
    button-text="Hapus Gelombang PMB"
/>
@endsection
@push('scripts')
@endpush
