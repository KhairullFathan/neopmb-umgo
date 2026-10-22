@extends('layouts.app')

@section('title', @$title)

@section('header')
<div class="page__headline">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb__item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb__item"><a href="{{ route('pmb.application.index') }}">Formulir PMB</a></li>
        <li class="breadcrumb__item" aria-current="page">Detail</li>
        </ol>
    </nav>
    <h1 class="page__title">Detail Formulir Pendaftaran PMB</h1>
    <h2 class="text-xl">#{{ $data->registration_number }} | {{ $data->applicant->name }}</h2>
    <p class="page__description">
        Detail gelombang pendaftaran penerimaan mahasiswa baru di Universitas Muhammadiyah Gorontalo
    </p>
</div>
<div class="page__action">
    <a href="{{ route('pmb.application.index') }}" class="button button--danger">
        <x-icon icon="back"/>
        Kembali
    </a>
</div>
@endsection

@section('content')
@php
    $pmbWavePath = $data->pmbWavePath()->first();
    $stages = $pmbWavePath->stages()
        ->where('is_active', true)
        ->with('pmbRegistrationStage')
        ->orderBy('sequence')
        ->get();
    $formulir = [
        (Object) ['label'=>'Nama', 'value'=> $data->applicant->name??'-'],
        (Object) ['label'=>'Nomor Identitas', 'value'=> $data->applicant->identity_number??'-'],
        (Object) ['label'=>'Jenis Kelamin', 'value'=> $data->applicant->gender??'-'],
        (Object) ['label'=>'Agama', 'value'=> $data->applicant->religion??'-'],
        (Object) ['label'=>'Tempat Lahir', 'value'=> $data->applicant->birth_place??'-'],
        (Object) ['label'=>'Tanggal Lahir', 'value'=> $data->applicant->birth_date??'-'],
        (Object) ['label'=>'Email', 'value'=> $data->applicant->user->email??'-'],
        (Object) ['label'=>'No Telephone (Whatsapp)', 'value'=> $data->applicant->phone??'-'],
        (Object) ['label'=>'Kewarganegaraan', 'value'=> $data->applicant->nationality??'-'],
        (Object) ['label'=>'Asal Negara', 'value'=> $data->applicant->country_of_origin??'-'],
        (Object) ['label'=>'Nama Gadis Ibu Kandung', 'value'=> $data->applicant->mother_name??'-'],
        (Object) ['label'=>'No Telephone Orang Tua', 'value'=> $data->applicant->parent_phone??'-'],
        (Object) ['label'=>'Provinsi', 'value'=> $data->applicant->village->district->regency->province->name??'-'],
        (Object) ['label'=>'Kabupaten / Kota', 'value'=> $data->applicant->village->district->regency->name??'-'],
        (Object) ['label'=>'Kecamatan', 'value'=> $data->applicant->village->district->name??'-'],
        (Object) ['label'=>'Desa / Kelurahan', 'value'=> $data->applicant->village->name??'-'],
        (Object) ['label'=>'Alamat', 'value'=> $data->applicant->address??'-'],
        (Object) ['label'=>'Asal', 'value'=> $data->applicant->school_origin??'-'],
        (Object) ['label'=>'Jenis', 'value'=> $data->applicant->school_type??'-'],
        (Object) ['label'=>'Jurusan / Program Studi', 'value'=> $data->applicant->school_major??'-'],
    ];
    $payment_stage = $stages->first(
        fn($stage) => $stage->pmbRegistrationStage->code === 'PEMBAYARAN_FORMULIR'
    );
    // dd($pmbWavePath, $stages);
@endphp
<section class="page__section">
    @include('components.alert')
    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-12 lg:col-span-6">
            <div class="flex flex-col gap-3">
                <div class="card">
                    <div class="card__header">
                        <span class="card__title">Formulir</span>
                    </div>
                    <div class="card__body">
                        <ul class="list-group">
                            @foreach ($formulir as $item)
                            <li class="list-group__item">
                                <span>{{ $item->label }}</span>
                                <span class="ms-auto text-muted-foreground">{{ $item->value }}</span>
                            </li>
                            @endforeach
                        </ul>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-6">
            <div class="flex flex-col gap-3">
                <div class="card">
                    <div class="card__header">
                        <span class="card__title">Berkas</span>
                    </div>
                    <div class="card__body">
                        <ul class="list-group">
                        @if ($payment_stage)
                        @php
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
                        <li class="list-group__item">
                            <span>Biaya Formulir <sup class="badge {{ $pay_status['class'] }}">{{ $pay_status['label'] }}</sup></span>
                            <span class="ms-auto text-muted-foreground">
                            @if ($payment && $payment->proof_path)
                                <a href="{{ Storage::url($payment->proof_path) }}" target="_blank" rel="noopener" class="button button--ghost button--primary">
                                    <x-icon icon="eye"/>
                                </a>
                                <button
                                    type="button"
                                    class="button button--secondary"
                                    data-stisla-dialog-trigger="formDialogFormulir"

                                    data-action="{{ route('pmb.application.payment.verify', $payment) }}"
                                    data-method="PUT"

                                    data-sender_account_name="{{ $payment->sender_account_name ?? "-" }}"
                                    data-sender_bank_name="{{ $payment->sender_bank_name ?? "-" }}"
                                    data-paid_amount="{{ $payment->paid_amount_formatted ?? "-" }}"
                                    data-paid_at="{{ $payment->paid_at?->format('Y-m-d') ?? "-" }}"
                                    data-verification_status="{{ $payment->verification_status ?? "-" }}"
                                    data-verification_notes="{{ $payment->verification_notes }}"
                                >
                                    <x-icon icon="pencil"/>
                                </button>
                            @else
                                <i>Belum upload</i>
                            @endif
                            </span>
                        </li>
                        @endif
                        @foreach ($data->pmbRegistrationPath->pmbRegistrationDocument as $req)
                        @php
                            $doc = $data->documents->firstWhere('pmb_registration_document_id', $req->id);
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
                        <li class="list-group__item">
                            <span>{{ $req->name }} <sup class="badge {{ $doc_status['class'] }}">{{ $doc_status['label'] }}</sup></span>
                            <span class="ms-auto text-muted-foreground">
                            @if ($doc && $doc->file_path)
                                <a href="{{ Storage::url($doc->file_path) }}" target="_blank" rel="noopener" class="button button--ghost button--primary">
                                    <x-icon icon="eye"/>
                                </a>
                                @if (($payment_stage && $payment?->verification_status==='approved') || (!$payment_stage))
                                <button
                                    type="button"
                                    class="button button--secondary"
                                    data-stisla-dialog-trigger="formDialogDocument"

                                    data-action="{{ route('pmb.application.document.verify', $doc) }}"
                                    data-method="PUT"

                                    data-verification_status="{{ $doc->verification_status ?? "-" }}"
                                    data-verification_notes="{{ $doc->verification_notes }}"
                                >
                                    <x-icon icon="pencil"/>
                                </button>
                                @endif
                            @else
                                <i>Belum upload</i>
                            @endif
                            </span>
                        </li>
                        @endforeach
                        <li class="list-group__item">
                            <span></span>
                            <span class="ms-auto text-muted-foreground"></span>
                        </li>
                        </ul>
                    </div>
                </div>
                <div class="card">
                    <div class="card__header">
                        <span class="card__title">Stage</span>
                    </div>
                    <div class="card__body">
                        <ul class="list-group">
                            <li class="list-group__item">
                                <span class="badge {{ $data->currentStageBadge['class'] }}">{{ $data->currentStageBadge['label'] }}</span>
                                <span class="ms-auto text-muted-foreground">
                                    <button
                                        type="button"
                                        class="button button--secondary"
                                        data-stisla-dialog-trigger="formDialogStage"

                                        data-action="{{ route('pmb.application.stage.set', $data) }}"
                                        data-method="PUT"

                                        data-current_stage="{{ $data->current_stage ?? "-" }}"
                                    >
                                        <x-icon icon="pencil"/>
                                    </button>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>

<x-form-dialog
    id="formDialogFormulir"
    title="Verifikasi Formulir"
    :fields="[
        [
            'name' => 'sender_account_name',
            'label' => 'Nama Rekening Pengirim',
            'type' => 'text',
            'placeholder' => '',
            'required' => true,
        ],
        [
            'name' => 'sender_bank_name',
            'label' => 'Nama Bank Pengirim',
            'type' => 'text',
            'placeholder' => '',
            'required' => true,
        ],
        [
            'name' => 'paid_amount',
            'label' => 'Nominal Transfer',
            'type' => 'currency',
            'placeholder' => '',
            'required' => false,
        ],
        [
            'name' => 'paid_at',
            'label' => 'Tanggal Transfer',
            'type' => 'date',
            'placeholder' => '',
            'required' => false,
        ],
        [
            'name' => 'verification_status',
            'label' => 'Status Verifikasi',
            'type' => 'select',
            'placeholder' => '',
            'required' => true,
            'options' => [
                'approved'=> 'Diterima',
                'rejected'=> 'Ditolak',
                'not_required'=> 'Tidak Wajib',
                'pending'=> 'Menunggu verifikasi',
            ]
        ],
        [
            'name' => 'verification_notes',
            'label' => 'Catatan Verifikasi',
            'type' => 'textarea',
            'placeholder' => '',
            'required' => false,
        ],
    ]"
/>
@php
    $stageOpt = [];
    foreach($stages as $st){
        $stageOpt[$st->pmbRegistrationStage->code] = $st->pmbRegistrationStage->name;
    }
@endphp
<x-form-dialog
    id="formDialogDocument"
    title="Verifikasi Berkas"
    :fields="[
        [
            'name' => 'verification_status',
            'label' => 'Status Verifikasi',
            'type' => 'select',
            'placeholder' => '',
            'required' => true,
            'options' => [
                'approved'=> 'Diterima',
                'rejected'=> 'Ditolak',
                'pending'=> 'Menunggu verifikasi',
            ]
        ],
        [
            'name' => 'verification_notes',
            'label' => 'Catatan Verifikasi',
            'type' => 'textarea',
            'placeholder' => '',
            'required' => false,
        ],
    ]"
/>
<x-form-dialog
    id="formDialogStage"
    title="Stage Baru Pendaftar"
    :fields="[
        [
            'name'=> 'current_stage',
            'label'=> 'Stage Selanjutnya',
            'type'=> 'select',
            'options' => $stageOpt,
            'required'=> true,
        ]
    ]"
/>
@endsection
@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-stisla-dialog-trigger]')
        .forEach(function (button) {
            button.addEventListener('click', function () {
                const dialogId = button.dataset.stislaDialogTrigger;
                const dialog = document.getElementById(dialogId);
                if (!dialog) {
                    return;
                }
                const form = dialog.querySelector('form');
                /*
                |--------------------------------------------------------------------------
                | Set action
                |--------------------------------------------------------------------------
                */

                if (button.dataset.action) {
                    form.action = button.dataset.action;
                }
                /*
                |--------------------------------------------------------------------------
                | Set method
                |--------------------------------------------------------------------------
                */
                const methodInput = form.querySelector('input[name="_method"]');
                if (methodInput) {
                    methodInput.value =
                        button.dataset.method || 'POST';
                }
                /*
                |--------------------------------------------------------------------------
                | Isi field
                |--------------------------------------------------------------------------
                */
                dialog.querySelectorAll('[name]')
                    .forEach(function (input) {
                        const name = input.name;
                        // Abaikan CSRF dan method
                        if (
                            name === '_token' ||
                            name === '_method'
                        ) {
                            return;
                        }
                        const value = button.dataset[name];
                        if (value === undefined) {
                            return;
                        }
                        if (input.type === 'checkbox') {
                            input.checked =
                                value === '1' ||
                                value === 'true';
                        } else if (input.type === 'radio') {
                            input.checked =
                                input.value === value;
                        } else {
                            input.value = value;
                        }
                    });
            });
        });
});
</script>
@endpush
