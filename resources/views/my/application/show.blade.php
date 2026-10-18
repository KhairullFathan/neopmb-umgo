@extends('layouts.app')

@section('title', @$title)

@push('styles')
<style>
    .pmb-stack { display: grid; gap: 1.5rem; }
    .pmb-row { display: grid; gap: 1.5rem; grid-template-columns: minmax(0, 1fr); align-items: stretch; }
    .pmb-row > .card { height: 100%; margin: 0; }
    @media (min-width: 992px) {
        .pmb-row--8-4 { grid-template-columns: minmax(0, 2fr) minmax(0, 1fr); }
        .pmb-row--5-7 { grid-template-columns: minmax(0, 5fr) minmax(0, 7fr); }
    }
    .pmb-hero { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1.25rem; }
    .pmb-hero__meta { display: flex; flex-wrap: wrap; gap: 2rem; }
    .pmb-hero__meta p { margin: 0; }
    .pmb-label { display: block; font-size: .75rem; text-transform: uppercase; letter-spacing: .05em; opacity: .65; margin-bottom: .2rem; }
    .pmb-dl { display: grid; grid-template-columns: repeat(auto-fit, minmax(11rem, 1fr)); gap: 1.1rem 1.5rem; margin: 0; }
    .pmb-dl dt { font-size: .75rem; text-transform: uppercase; letter-spacing: .05em; opacity: .65; margin: 0 0 .15rem; }
    .pmb-dl dd { margin: 0; font-weight: 600; word-break: break-word; }
    .pmb-scroll { max-height: 26rem; overflow-y: auto; padding-right: .5rem; }
    .pmb-inline-form { display: grid; grid-template-columns: repeat(auto-fit, minmax(14rem, 1fr)); gap: 1rem; align-items: end; }
    .pmb-inline-form .form__group { margin: 0; }
    .pmb-stack-form { display: grid; gap: 1rem; }
    .pmb-stack-form .form__group { margin: 0; }
</style>
@endpush

@php
    $current_stage = $data->current_stage;
    $pmbWavePath = $data->pmbWavePath()->first();
    $stages = $pmbWavePath->stages()
        ->where('is_active', true)
        ->with('pmbRegistrationStage')
        ->orderBy('sequence')
        ->get();

    $currentIndex = $stages->search(
        fn ($stage) => $stage->pmbRegistrationStage->code === $current_stage
    );
    $bank = \App\Models\BankAccount::where('name','Bank Syariah Indonesia (BSI)')->first();

    $stages = $stages->map(function ($stage, $index) use ($currentIndex) {
        if ($currentIndex === false) {
            $stage->state = 'upcoming';
        } elseif ($index < $currentIndex) {
            $stage->state = 'done';
        } elseif ($index === $currentIndex) {
            $stage->state = 'current';
        } else {
            $stage->state = 'upcoming';
        }
        return $stage;
    });
    $paymentLocked = $payment && in_array($payment->verification_status, ['approved']);
@endphp

@section('header')
<div class="page__headline">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb__item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb__item"><a href="{{ route('my.application.index') }}">Pendaftaran Saya</a></li>
            <li class="breadcrumb__item" aria-current="page">Detail Pendaftaran</li>
        </ol>
    </nav>
    <h1 class="page__title">Data Saya</h1>
    <p class="page__description">
        Data pendaftaran saya
    </p>
</div>
<div class="page__action">
    <span class="badge {{ $data->status_badge['class'] }}">{{ $data->status_badge['label'] }}</span>
</div>
@endsection

@section('content')
<section class="page__section">
    @include('components.alert')
</section>

<section class="page__section">
    <div class="grid grid-cols-12 gap-4">

        {{-- ===================== BARIS 1 : STATUS TERKINI (FULL) ===================== --}}
        {{-- 1. STATUS TERKINI --}}
        <article class="card col-span-12">
            <div class="card__body">
                <div class="pmb-hero">
                    <div>
                        <span class="pmb-label">Status Terkini</span>
                        <p>
                            <span class="badge {{ $data->status_badge['class'] }}">{{ $data->status_badge['label'] }}</span>
                        </p>
                        @if ($data->status_note)
                            <p>{{ $data->status_note }}</p>
                        @endif
                    </div>
                    <div class="pmb-hero__meta">
                        <p>
                            <span class="pmb-label">Tahap saat ini</span>
                            <span class="badge {{ $data->current_stage_badge['class'] }}"><strong>{{ $data->current_stage_badge['label'] }}</strong></span>
                        </p>
                        <p class="text-muted">
                            <span class="pmb-label">Terakhir diperbarui</span>
                            {{ optional($data->updated_at)->diffForHumans() }}
                        </p>
                    </div>
                </div>
            </div>
        </article>

        <article class="card col-span-12">
            <div class="card__body">
                <span class="pmb-label">Informasi Pembayaran Formulir</span>
                <p>Lakukan transfer ke rekening resmi berikut, lalu unggah bukti pembayaran pada bagian <strong>Upload Pembayaran Formulir <a href="#pembayaran">di bawah</a></strong>.</p>

                <div class="pmb-bank">
                    <div class="pmb-bank__item">
                        <span class="pmb-label">Bank</span>
                        <strong>{{ $bank->name }}</strong>
                    </div>

                    <div class="pmb-bank__item">
                        <span class="pmb-label">Nomor Rekening</span>
                        <div class="pmb-bank__copy">
                            <strong id="pmbNoRek">{{ $bank->account_number }}</strong>
                            {{-- <button type="button" class="button button--sm button--primary" id="pmbCopyRek" aria-label="Salin nomor rekening">Salin</button> --}}
                        </div>
                    </div>

                    <div class="pmb-bank__item">
                        <span class="pmb-label">Atas Nama</span>
                        <strong>{{ $bank->account_name }}</strong>
                    </div>

                    <div class="pmb-bank__item">
                        <span class="pmb-label">Nominal Transfer</span>
                        <strong>{{ $data->pmbWavePath()->first()?->registration_fee_formatted }}</strong>
                    </div>
                </div>

                <ol class="pmb-bank__steps">
                    <li>Transfer sesuai nominal yang tertera, tanpa pembulatan.</li>
                    <li>Cantumkan <strong>nomor registrasi dan nama lengkap</strong> pada berita transfer.</li>
                    <li>Simpan bukti transfer (foto atau tangkapan layar yang jelas).</li>
                    <li>Unggah bukti pada bagian <strong>Upload Pembayaran Formulir</strong>. Panitia akan memverifikasi dalam [1x24 jam hari kerja].</li>
                </ol>

                <p class="text-muted">Pastikan nama dan nomor rekening sesuai dengan yang tercantum di sini. Panitia tidak pernah meminta transfer ke rekening pribadi.</p>
            </div>
        </article>
        {{-- ===================== BARIS 2 : INFO (LEBAR) + PEMBAYARAN (KECIL) ===================== --}}
        {{-- 2. INFORMASI PENDAFTARAN --}}
        <article class="card col-span-12 lg:col-span-8">
            <header class="card__header">
                <h2 class="card__title">Informasi Pendaftaran</h2>
            </header>
            <div class="card__body">
                <dl class="pmb-dl">
                    <div>
                        <dt>No. Pendaftaran</dt>
                        <dd>{{ $data->registration_number }}</dd>
                    </div>
                    <div>
                        <dt>Nama Lengkap</dt>
                        <dd>{{ $data->applicant->name }}</dd>
                    </div>
                    <div>
                        <dt>Email</dt>
                        <dd>{{ $data->applicant->email ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt>No. WhatsApp</dt>
                        <dd>{{ $data->applicant->phone ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt>Gelombang</dt>
                        <dd>{{ $data->pmbWave->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt>Jalur</dt>
                        <dd>{{ $data->pmbRegistrationPath->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt>Program Studi</dt>
                        <dd>
                            @foreach ($data->studyPrograms as $item)
                            <span class="block">{{ $item->choice_number }}. {{ $item->studyProgram->degree_level }} {{ $item->studyProgram->name }}</span>
                            @endforeach
                        </dd>
                    </div>
                    <div>
                        <dt>Tanggal Daftar</dt>
                        <dd>{{ optional($data->created_at)->translatedFormat('d F Y, H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </article>

        {{-- 3. UPLOAD PEMBAYARAN FORMULIR --}}
        <article class="card col-span-12 lg:col-span-4" id="pembayaran">
            <header class="card__header">
                <h2 class="card__title">Pembayaran Formulir</h2>
            </header>
            <div class="card__body">
                <dl class="pmb-dl">
                    <div>
                        <dt>Biaya Formulir</dt>
                        <dd>{{ $data->pmbWavePath()->first()?->registration_fee_formatted }}</dd>
                    </div>
                    <div>
                        <dt>Status</dt>
                        <dd>
                            @php
                                [$payLabel, $payTone] = match ($payment->verification_status ?? null) {
                                    'approved' => ['Lunas', 'success'],
                                    'pending'  => ['Menunggu verifikasi', 'info'],
                                    'rejected' => ['Ditolak', 'danger'],
                                    default    => ['Belum dibayar', 'warning'],
                                };
                            @endphp
                            <span class="badge badge--{{ $payTone }}">{{ $payLabel }}</span>
                        </dd>
                    </div>
                </dl>


                @if (($payment->verification_status ?? null) === 'rejected')
                    <p class="text-danger">{{ $payment->verification_notes }}</p>
                @elseif(($payment->verification_status ?? null) === 'approved')
                    <p class="text-success">{{ $payment->verification_notes }}</p>
                @endif

                @if ($payment && $payment->proof_path)
                    <p>
                        <a href="{{ Storage::url($payment->proof_path) }}" target="_blank" rel="noopener">
                            Lihat bukti pembayaran
                        </a>
                    </p>
                @endif

                @unless ($paymentLocked)
                    <hr>
                    <form action="{{ route('my.application.payments.store', $data) }}"
                          method="POST" enctype="multipart/form-data" class="pmb-stack-form">
                        @csrf
                        <div class="field">
                            <label for="paid_at" class="field__label">Tanggal Bayar</label>
                            <input
                                type="date"
                                class="input"
                                id="paid_at"
                                name="paid_at"

                                value="{{ old('paid_at') }}"
                            />
                            @error('paid_at')
                                <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="field">
                            <label for="file" class="field__label">File</label>
                            <input
                                type="file"
                                class="input"
                                id="file"
                                name="file"

                                value="{{ old('file') }}"
                            />
                            <small>Maksimal 2 MB</small>
                            @error('file')
                                <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <button type="submit" class="button button--primary">
                                <x-icon icon="save"/>
                                Simpan
                            </button>
                        </div>
                    </form>
                @endunless
            </div>
        </article>

        {{-- ===================== BARIS 3 : TIMELINE (KECIL) + BERKAS (LEBAR) ===================== --}}
        {{-- 4. TIMELINE TAHAPAN --}}
        <article class="card col-span-12 lg:col-span-3">
            <header class="card__header">
                <h2 class="card__title">Timeline Tahapan</h2>
            </header>
            <div class="card__body">
                <div class="pmb-scroll">
                    <ol class="timeline">
                        @php
                            $current_stage = $data->current_stage;
                            $state = '';
                        @endphp
                        @forelse ($stages as $stage)
                            @php
                                $tone = match ($stage->state) {
                                    'done'    => 'success',
                                    'current' => 'primary',
                                    default   => 'warning',
                                };
                            @endphp
                            <li class="timeline__item timeline__item--{{ $tone }}"
                                @if ($stage->state === 'current') aria-current="step" @endif>
                                <div class="timeline__marker timeline__marker--{{$tone}}"></div>
                                <div class="timeline__content">
                                    <h3 class="timeline__title">{{ $stage->pmbRegistrationStage->name }}</h3>
                                    <p class="timeline__meta">
                                        @if ($stage->execution_type=='scheduled')
                                            {{ optional($stage->start_at)->translatedFormat('d M Y') }}
                                            @if ($stage->end_date)
                                                – {{ $stage->end_at->translatedFormat('d M Y') }}
                                            @endif
                                        @endif
                                    </p>
                                    @if ($stage->pmbRegistrationStage->description)
                                        <p>{{ $stage->description }}</p>
                                    @endif
                                </div>
                            </li>
                        @empty
                            <li class="timeline__item">
                                <div class="timeline__content">Belum ada tahapan untuk gelombang ini.</div>
                            </li>
                        @endforelse
                    </ol>
                </div>
            </div>
        </article>

        {{-- 5. BERKAS YANG DIPERLUKAN --}}
        <article class="card col-span-12 lg:col-span-9">
            <header class="card__header">
                <h2 class="card__title">Berkas yang Diperlukan</h2>
            </header>
            <div class="card__body">
                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Berkas</th>
                                <th>Ketentuan</th>
                                <th>Status</th>
                                <th class="text-end">File</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data->pmbRegistrationPath->pmbRegistrationDocument as $req)
                                @php
                                    $doc = $data->documents->firstWhere('pmb_registration_document_id', $req->id);
                                    $docTone = match ($doc->verification_status ?? null) {
                                        'verified' => 'success',
                                        'rejected' => 'danger',
                                        'pending'  => 'info',
                                        default    => 'secondary',
                                    };
                                    $docLabel = match ($doc->verification_status ?? null) {
                                        'verified' => 'Terverifikasi',
                                        'rejected' => 'Ditolak',
                                        'pending'  => 'Menunggu verifikasi',
                                        default    => 'Belum diunggah',
                                    };
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $req->name }}</strong>
                                        @if ($req->is_required)
                                            <span class="badge badge--danger">Wajib</span>
                                        @endif
                                    </td>
                                    <td class="text-muted">{{ $req->allowed_mimes_label }}</td>
                                    <td>
                                        <span class="badge badge--{{ $docTone }}">{{ $docLabel }}</span>
                                        @if (($doc->status ?? null) === 'rejected' && $doc->reject_reason)
                                            <p class="text-danger">{{ $doc->reject_reason }}</p>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if ($doc)
                                            <a href="{{ Storage::url($doc->file_path) }}" target="_blank" rel="noopener" class="btn btn--sm btn--outline-primary">Lihat</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4">Tidak ada berkas yang dipersyaratkan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </article>

        {{-- ===================== BARIS 4 : UPLOAD BERKAS (FULL, FORM SEJAJAR) ===================== --}}
        {{-- 6. UPLOAD BERKAS --}}
        <article class="card col-span-12" id="upload-berkas">
            <header class="card__header">
                <h2 class="card__title">Upload Berkas</h2>
            </header>
            <div class="card__body">
                <form action="{{ route('my.application.documents.store', $data) }}"
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="grid grid-cols-2 gap-4">
                        <div class="field">
                            <label for="pmb_registration_document_id" class="field__label">Berkas</label>
                            <select name="pmb_registration_document_id" id="pmb_registration_document_id" class="select">
                                <option value="">Pilih Opsi</option>
                                @foreach ($data->pmbRegistrationPath->pmbRegistrationDocument as $req)
                                    <option value="{{ $req->id }}" @selected(old('pmb_registration_document_id') == $req->id)>
                                        {{ $req->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pmb_registration_document_id')
                                <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="field">
                            <label for="file" class="field__label">File</label>
                            <input
                                type="file"
                                class="input"
                                id="file"
                                name="file"

                                value="{{ old('file') }}"
                            />
                            <small>Maksimal 2 MB</small>
                            @error('file')
                                <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <button type="submit" class="button button--primary">
                                <x-icon icon="save"/>
                                Simpan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </article>

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
<script>

document.addEventListener('DOMContentLoaded', function () {
    const button = document.getElementById('pmbCopyRek');
    const rekening = document.getElementById('pmbNoRek');

    if (!button || !rekening) return;

    const label = button.querySelector('span');

    button.addEventListener('click', async function () {
        const number = rekening.textContent.trim();

        try {
            await navigator.clipboard.writeText(number);

            if (label) label.textContent = 'Tersalin!';

            button.setAttribute('aria-label', 'Nomor rekening berhasil disalin');

            setTimeout(function () {
                if (label) label.textContent = 'Salin';

                button.setAttribute('aria-label', 'Salin nomor rekening');
            }, 2000);
        } catch (error) {
            console.error('Gagal menyalin nomor rekening:', error);
        }
    });
});

</script>
@endpush
