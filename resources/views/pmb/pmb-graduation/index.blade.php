@extends('layouts.app')

@section('title', @$title)

@section('header')
<div class="page__headline">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb__item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb__item" aria-current="page">Kelulucan PMB</li>
        </ol>
    </nav>
    <h1 class="page__title">Data Kelulusan PMB</h1>
    <p class="page__description">
        Daftar kelulusan pendaftaran mahasiswa baru di Universitas Muhammadiyah Gorontalo
    </p>
</div>
<div class="page__action">
    {{-- <a href="{{ route('pmb.wave.create') }}" class="button button--primary">
        <x-icon icon="plus" />
        Tambah
    </a> --}}
</div>
@endsection

@section('content')
<section class="page__section">
    @include('components.alert')
    <div class="card">
        <form method="get" action="">
            <div class="card__header flex-wrap">
                <div></div>
                <div class="input-group ms-auto w-full md:w-60 mb-4 md:mb-0" role="search">
                    <span class="input-group__text">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="1em"
                            height="1em"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                        >
                            <g fill="none" stroke="currentColor" stroke-width="1.5">
                            <circle cx="11.5" cy="11.5" r="9.5" />
                            <path stroke-linecap="round" d="M18.5 18.5L22 22" />
                            </g>
                        </svg>
                    </span>
                    <input
                        type="search"
                        name="search"
                        class="input"
                        placeholder="Pencarian..."
                        aria-label="Pencarian"
                        value="{{ request('search') }}"
                    />
                </div>
                <div class="field--inline">
                    <select name="period_id" id="period_id" class="select">
                        <option value="">Pilih Periode PMB</option>
                        @foreach ($periods as $item=>$name)
                        <option value="{{ $item }}" {{ old('period_id') == $item ? 'selected':'' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
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


        <div class="table-responsive">
            <table class="table table--hover table--align-middle">
                <thead class="table__head--alt">
                    <tr>
                        <th scope="col">Kelulusan</th>
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
                        <td scope="row">
                            <div class="field w-auto">
                                <form class="graduate-form"
                                    enctype="multipart/form-data"
                                    action="{{ route('pmb.graduation.store', $row) }}"
                                    data-url="{{ route('pmb.graduation.store', $row) }}"
                                    data-current-code="{{ $row->graduate?->study_program_code ?? '' }}"
                                >
                                @csrf
                                @foreach ($row->studyPrograms as $item_ps)
                                <div class="field__item">
                                    <input
                                        name="study_program_code"
                                        value="{{ $item_ps->studyProgram->code }}"
                                        class="checkbox graduate-checkbox"
                                        type="checkbox"
                                        id="checkbox-{{ $row->id }}-{{ $item_ps->studyProgram->code }}"
                                        @checked(
                                            $row->graduate?->study_program_code == $item_ps->studyProgram->code
                                        )
                                    />

                                    <label
                                        class="field__label"
                                        for="checkbox-{{ $row->id }}-{{ $item_ps->studyProgram->code }}"
                                    >
                                        Pil. {{ $item_ps->choice_number }}
                                        {{ $item_ps->studyProgram->degree_level }}
                                        - {{ $item_ps->studyProgram->name }}
                                        ({{ $item_ps->studyProgram->code }})
                                    </label>
                                </div>
                                <div class="graduate-progress mt-2" hidden>
                                    <div class="progress">
                                        <div
                                            class="progress-bar progress-bar-striped progress-bar-animated"
                                            role="progressbar"
                                            style="width: 100%"
                                        >
                                            Memproses kelulusan...
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        Mohon tunggu, jangan tutup halaman.
                                    </small>
                                </div>

                                <div
                                    class="graduate-feedback mt-2"
                                    role="status"
                                    aria-live="polite"
                                ></div>
                                @endforeach
                                </form>
                            </div>
                        </td>
                        <td scope="row">
                            <div class="text-lg font-bold">{{ $row->applicant->name }}</div>
                            <div class="">{{ $row->applicant->registration_number??'-' }}</div>
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
                                {{-- <a
                                    href="{{ route('pmb.application.show', $row) }}" target="_blank"
                                    class="button button--ghost button--primary button--icon-only button--sm"
                                    aria-label="Detail"
                                >
                                    <x-icon icon="eye"/>
                                </a> --}}
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
@endsection
@push('scripts')
<script>

document.querySelectorAll('.graduate-form').forEach((form) => {
    const checkboxes = form.querySelectorAll('.graduate-checkbox');
    const progress = form.querySelector('.graduate-progress');
    const feedback = form.querySelector('.graduate-feedback');

    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener('change', async () => {
            // Checkbox yang sudah dipilih tidak bisa dibatalkan
            // hanya dengan mengkliknya kembali.
            console.log(form.dataset.url);
            if (!checkbox.checked) {
                checkbox.checked =
                    checkbox.value === form.dataset.currentCode;
                return;
            }

            const previousCode = form.dataset.currentCode;

            // Cegah perubahan selama request berlangsung.
            checkboxes.forEach((item) => {
                item.disabled = true;
            });

            progress.hidden = false;
            feedback.textContent = 'Mengirim keputusan kelulusan...';
            feedback.className = 'graduate-feedback mt-2 text-muted';

            try {
                const response = await fetch(form.dataset.url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': form.querySelector(
                            'input[name="_token"]'
                        ).value,
                    },
                    body: JSON.stringify({
                        study_program_code: checkbox.value,
                    }),
                });

                const result = await response.json();

                if (!response.ok || !result.success) {
                    const validationErrors = result.errors
                        ? Object.values(result.errors).flat().join(' ')
                        : null;

                    throw new Error(
                        validationErrors ||
                        result.message ||
                        'Gagal menetapkan kelulusan.'
                    );
                }

                // Sinkronkan pilihan dengan keputusan dari server.
                form.dataset.currentCode = checkbox.value;

                checkboxes.forEach((item) => {
                    item.checked = item.value === checkbox.value;
                });

                feedback.textContent = result.message;
                feedback.className =
                    'graduate-feedback mt-2 text-success';

            } catch (error) {
                // Kembalikan tampilan ke keputusan sebelumnya.
                checkboxes.forEach((item) => {
                    item.checked = item.value === previousCode;
                });

                feedback.textContent = error.message ||
                    'Terjadi kesalahan jaringan. Silakan coba kembali.';

                feedback.className =
                    'graduate-feedback mt-2 text-danger';

            } finally {
                progress.hidden = true;

                checkboxes.forEach((item) => {
                    item.disabled = false;
                });
            }
        });
    });
});
</script>
@endpush
