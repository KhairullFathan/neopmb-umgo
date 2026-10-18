@extends('layouts.app-fe')

@section('title', @$title)

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
    // payment stage
    $payment_locked = $payment && in_array($payment->verification_status, ['approved']);
    $payment_stage = $stages->first(
        fn($stage) => $stage->pmbRegistrationStage->code === 'PEMBAYARAN_FORMULIR'
    );
    // exam stage
    $exam_stage = $stages->first(
        fn ($stage) => $stage->pmbRegistrationStage->code === 'UJIAN_ONLINE'
    );
    // $exam_open = $exam_stage && $current_stage === 'UJIAN_ONLINE';
    $exam_open = ($exam_stage) && ($payment_stage && $payment?->verification_status === 'approved') && ($current_stage == 'UJIAN_ONLINE');
    // dd($exam_open);
    $exam_url = route('my.exam.start', $data);
@endphp

@section('content')

{{-- Header --}}
<section class="fe-hero">
    <div class="fe-hero__inner">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <nav aria-label="Breadcrumb" class="fe-hero__crumb">
                    <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a>
                    <span class="mx-1">/</span>
                    <a href="{{ route('my.application.index') }}" class="hover:underline">Pendaftaran Saya</a>
                    <span class="mx-1">/</span>
                    <span aria-current="page">Detail Pendaftaran</span>
                </nav>
                <h1 class="fe-hero__title">Detail Pendaftaran</h1>
                <p class="fe-hero__desc">Data pendaftaran saya</p>
            </div>
            <span class="rounded-full bg-white p-1 shadow">
                <span class="fe-badge {{ $data->status_badge['class'] }}">{{ $data->status_badge['label'] }}</span>
            </span>
        </div>
    </div>
</section>

<section class="bg-white py-10">
    <div class="mx-auto max-w-6xl px-4">

        @include('components.fe.alert')

        <div class="grid gap-6 lg:grid-cols-12">

            {{-- ===================== BARIS 1 : STATUS TERKINI (FULL) ===================== --}}
            {{-- 1. STATUS TERKINI --}}
            <article class="fe-card border-l-4 border-l-brand-600 lg:col-span-12">
                <div class="fe-card__body">
                    <div class="flex flex-wrap items-center justify-between gap-6">
                        <div>
                            <span class="fe-meta__label">Status Terkini</span>
                            <p class="mt-1.5">
                                <span class="fe-badge {{ $data->status_badge['class'] }}">{{ $data->status_badge['label'] }}</span>
                            </p>
                            @if ($data->status_note)
                                <p class="mt-2 text-sm text-slate-600">{{ $data->status_note }}</p>
                            @endif
                        </div>
                        <div class="flex flex-wrap gap-10">
                            <p>
                                <span class="fe-meta__label mb-1.5 block">Tahap saat ini</span>
                                <span class="fe-badge {{ $data->current_stage_badge['class'] }}"><strong>{{ $data->current_stage_badge['label'] }}</strong></span>
                            </p>
                            <p class="text-sm text-slate-600">
                                <span class="fe-meta__label mb-1.5 block">Terakhir diperbarui</span>
                                {{ optional($data->updated_at)->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>
            </article>

            {{-- ===================== UJIAN ONLINE (hanya jika alur UJIAN_ONLINE ada) ===================== --}}
            @if ($exam_stage && $exam_open)
            <article class="fe-card lg:col-span-12" id="ujian">
                <div class="fe-card__body">
                    <div class="flex flex-wrap items-center justify-between gap-6">
                        <div class="flex items-start gap-4">
                            <span class="grid h-12 w-12 shrink-0 place-items-center rounded-full bg-brand-100 text-2xl" aria-hidden="true">📝</span>
                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <h2 class="text-lg font-bold text-slate-900">{{ $exam_stage->pmbRegistrationStage->name ?? 'Ujian Online' }}</h2>
                                    @if ($exam_open && $exam_stage->execution_type=='flexible')
                                        <span class="fe-badge fe-badge--success">Siap dimulai</span>
                                    @else
                                        <span class="fe-badge fe-badge--secondary">Belum dibuka</span>
                                    @endif
                                </div>
                                @if ($exam_stage->execution_type == 'scheduled')
                                    <p class="mt-1 text-sm text-slate-600">
                                        Jadwal:
                                        <strong>{{ optional($exam_stage->start_at)->translatedFormat('d M Y, H:i') }}</strong>
                                        @if ($exam_stage->end_at)
                                            s/d <strong>{{ $exam_stage->end_at->translatedFormat('d M Y, H:i') }}</strong>
                                        @endif
                                    </p>
                                @endif
                                <p class="mt-1 text-sm text-slate-600">
                                    @if ($exam_open)
                                        Pastikan koneksi internet stabil sebelum memulai ujian.
                                    @else
                                        Tombol akan aktif saat pendaftaran Anda berada pada tahap ujian online.
                                    @endif
                                </p>
                            </div>
                        </div>
                        <a href="{{ $exam_open ? $exam_url : '#' }}"
                           class="fe-btn fe-btn--primary"
                           @unless ($exam_open) aria-disabled="true" tabindex="-1" @endunless>
                            Mulai Ujian
                        </a>
                    </div>
                </div>
            </article>
            @endif

            {{-- ===================== BARIS 2 : INFO REKENING (LEBAR) + UPLOAD PEMBAYARAN (KECIL) ===================== --}}
            @if ($payment_stage)
            <article class="fe-card lg:col-span-7">
                <div class="fe-card__header">
                    <h2 class="fe-card__title">Informasi Pembayaran Formulir</h2>
                </div>
                <div class="fe-card__body">
                    <p class="text-sm text-slate-600">Lakukan transfer ke rekening resmi berikut, lalu unggah bukti pembayaran pada bagian <strong>Upload Pembayaran Formulir <a href="#pembayaran" class="text-brand-600 underline">di bawah</a></strong>.</p>

                    <div class="mt-5 grid gap-4 rounded-xl bg-brand-50 p-5 sm:grid-cols-2">
                        <div>
                            <span class="fe-meta__label">Bank</span>
                            <div class="fe-meta__value">{{ $bank->name }}</div>
                        </div>

                        <div>
                            <span class="fe-meta__label">Nomor Rekening</span>
                            <div class="mt-0.5 flex items-center gap-2">
                                <strong id="pmbNoRek" class="text-lg text-brand-700">{{ $bank->account_number }}</strong>
                                <button type="button" class="fe-btn fe-btn--primary fe-btn--sm" id="pmbCopyRek" aria-label="Salin nomor rekening"><span>Salin</span></button>
                            </div>
                        </div>

                        <div>
                            <span class="fe-meta__label">Atas Nama</span>
                            <div class="fe-meta__value">{{ $bank->account_name }}</div>
                        </div>

                        <div>
                            <span class="fe-meta__label">Nominal Transfer</span>
                            <div class="fe-meta__value">{{ $data->pmbWavePath()->first()?->registration_fee_formatted }}</div>
                        </div>
                    </div>

                    <ol class="mt-5 list-decimal space-y-1.5 pl-5 text-sm text-slate-700">
                        <li>Transfer sesuai nominal yang tertera, tanpa pembulatan.</li>
                        <li>Cantumkan <strong>nomor registrasi dan nama lengkap</strong> pada berita transfer.</li>
                        <li>Simpan bukti transfer (foto atau tangkapan layar yang jelas).</li>
                        <li>Unggah bukti pada bagian <strong>Upload Pembayaran Formulir</strong>. Panitia akan memverifikasi dalam [1x24 jam hari kerja].</li>
                    </ol>

                    <p class="mt-4 rounded-lg bg-amber-50 px-4 py-3 text-xs text-amber-800">Pastikan nama dan nomor rekening sesuai dengan yang tercantum di sini. Panitia tidak pernah meminta transfer ke rekening pribadi.</p>
                </div>
            </article>

            <article class="fe-card lg:col-span-5" id="pembayaran">
                <div class="fe-card__header">
                    <h2 class="fe-card__title">Pembayaran Formulir</h2>
                </div>
                <div class="fe-card__body">
                    <dl class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="fe-meta__label">Biaya Formulir</dt>
                            <dd class="fe-meta__value">{{ $data->pmbWavePath()->first()?->registration_fee_formatted }}</dd>
                        </div>
                        <div>
                            <dt class="fe-meta__label">Status</dt>
                            <dd class="mt-1">
                                @php
                                    [$payLabel, $payTone] = match ($payment->verification_status ?? null) {
                                        'approved' => ['Lunas', 'success'],
                                        'pending'  => ['Menunggu verifikasi', 'info'],
                                        'rejected' => ['Ditolak', 'danger'],
                                        default    => ['Belum dibayar', 'warning'],
                                    };
                                @endphp
                                <span class="fe-badge fe-badge--{{ $payTone }}">{{ $payLabel }}</span>
                            </dd>
                        </div>
                    </dl>


                    @if (($payment->verification_status ?? null) === 'rejected')
                        <p class="mt-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">{{ $payment->verification_notes }}</p>
                    @elseif(($payment->verification_status ?? null) === 'approved')
                        <p class="mt-4 rounded-lg bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ $payment->verification_notes }}</p>
                    @endif

                    @if ($payment && $payment->proof_path)
                        <p class="mt-4 text-sm">
                            <a href="{{ Storage::url($payment->proof_path) }}" target="_blank" rel="noopener" class="font-medium text-brand-600 underline">
                                Lihat bukti pembayaran
                            </a>
                        </p>
                    @endif

                    @unless ($payment_locked)
                        <hr class="my-5 border-brand-100">
                        <form action="{{ route('my.application.payments.store', $data) }}"
                              method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label for="paid_at" class="fe-label">Tanggal Bayar</label>
                                <input
                                    type="date"
                                    class="fe-input"
                                    id="paid_at"
                                    name="paid_at"

                                    value="{{ old('paid_at') }}"
                                />
                                @error('paid_at')
                                    <p class="fe-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="payment_file" class="fe-label">File</label>
                                <input
                                    type="file"
                                    class="fe-input"
                                    id="payment_file"
                                    name="file"

                                    value="{{ old('file') }}"
                                />
                                <small class="mt-1 block text-xs text-slate-500">Maksimal 2 MB</small>
                                @error('file')
                                    <p class="fe-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <button type="submit" class="fe-btn fe-btn--primary fe-btn--block">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    @endunless
                </div>
            </article>
            @endif

            {{-- ===================== BARIS 3 : INFO + BERKAS (LEBAR) | TIMELINE (KECIL, MEMANJANG) ===================== --}}
            {{-- 2. INFORMASI PENDAFTARAN --}}
            <article class="fe-card lg:col-span-8">
                <div class="fe-card__header">
                    <h2 class="fe-card__title">Informasi Pendaftaran</h2>
                </div>
                <div class="fe-card__body">
                    <dl class="grid gap-x-6 gap-y-5 sm:grid-cols-2 xl:grid-cols-3">
                        <div>
                            <dt class="fe-meta__label">No. Pendaftaran</dt>
                            <dd class="fe-meta__value">{{ $data->registration_number }}</dd>
                        </div>
                        <div>
                            <dt class="fe-meta__label">Nama Lengkap</dt>
                            <dd class="fe-meta__value">{{ $data->applicant->name }}</dd>
                        </div>
                        <div>
                            <dt class="fe-meta__label">Email</dt>
                            <dd class="fe-meta__value break-words">{{ $data->applicant->email ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="fe-meta__label">No. WhatsApp</dt>
                            <dd class="fe-meta__value">{{ $data->applicant->phone ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="fe-meta__label">Gelombang</dt>
                            <dd class="fe-meta__value">{{ $data->pmbWave->name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="fe-meta__label">Jalur</dt>
                            <dd class="fe-meta__value">{{ $data->pmbRegistrationPath->name ?? '-' }}</dd>
                        </div>
                        <div class="sm:col-span-2 xl:col-span-2">
                            <dt class="fe-meta__label">Program Studi</dt>
                            <dd class="fe-meta__value">
                                @foreach ($data->studyPrograms as $item)
                                <span class="block">{{ $item->choice_number }}. {{ $item->studyProgram->degree_level }} {{ $item->studyProgram->name }}</span>
                                @endforeach
                            </dd>
                        </div>
                        <div>
                            <dt class="fe-meta__label">Tanggal Daftar</dt>
                            <dd class="fe-meta__value">{{ optional($data->created_at)->translatedFormat('d F Y, H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </article>

            {{-- 4. TIMELINE TAHAPAN --}}
            <article class="fe-card lg:col-span-4 lg:row-span-2">
                <div class="fe-card__header">
                    <h2 class="fe-card__title">Timeline Tahapan</h2>
                </div>
                <div class="fe-card__body">
                    <ol class="fe-timeline">
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
                            <li class="fe-timeline__item"
                                @if ($stage->state === 'current') aria-current="step" @endif>
                                <div class="fe-timeline__marker fe-timeline__marker--{{$tone}}"></div>
                                <div>
                                    <h3 class="fe-timeline__title">{{ $stage->pmbRegistrationStage->name }}</h3>
                                    <p class="fe-timeline__meta">
                                        @if ($stage->execution_type=='scheduled')
                                            {{ optional($stage->start_at)->translatedFormat('d M Y') }}
                                            @if ($stage->end_date)
                                                – {{ $stage->end_at->translatedFormat('d M Y') }}
                                            @endif
                                        @endif
                                    </p>
                                    @if ($stage->pmbRegistrationStage->description)
                                        <p class="mt-1 text-sm text-slate-600">{{ $stage->description }}</p>
                                    @endif
                                </div>
                            </li>
                        @empty
                            <li class="fe-timeline__item">
                                <div class="text-sm text-slate-600">Belum ada tahapan untuk gelombang ini.</div>
                            </li>
                        @endforelse
                    </ol>
                </div>
            </article>

            {{-- 5. BERKAS YANG DIPERLUKAN --}}
            <article class="fe-card lg:col-span-8">
                <div class="fe-card__header">
                    <h2 class="fe-card__title">Berkas yang Diperlukan</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="fe-table">
                        <thead>
                            <tr>
                                <th>Berkas</th>
                                <th>Ketentuan</th>
                                <th>Status</th>
                                <th class="text-right">File</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data->pmbRegistrationPath->pmbRegistrationDocument as $req)
                                @php
                                    $doc = $data->documents->firstWhere('pmb_registration_document_id', $req->id);
                                    $docTone = match ($doc->verification_status ?? null) {
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                        'pending'  => 'info',
                                        default    => 'secondary',
                                    };
                                    $docLabel = match ($doc->verification_status ?? null) {
                                        'approved' => 'Terverifikasi',
                                        'rejected' => 'Ditolak',
                                        'pending'  => 'Menunggu verifikasi',
                                        default    => 'Belum diunggah',
                                    };
                                @endphp
                                <tr>
                                    <td>
                                        <strong class="text-slate-800">{{ $req->name }}</strong>
                                        @if ($req->is_required)
                                            <span class="fe-badge fe-badge--danger">Wajib</span>
                                        @endif
                                        <br>
                                        <span>{{ @$req->description }}</span>
                                    </td>
                                    <td class="text-slate-500">{{ $req->allowed_mimes_label }}</td>
                                    <td>
                                        <span class="fe-badge fe-badge--{{ $docTone }}">{{ $docLabel }}</span>
                                        @if (($doc->status ?? null) === 'rejected' && $doc->reject_reason)
                                            <p class="mt-1 text-xs text-red-600">{{ $doc->reject_reason }}</p>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if ($doc)
                                            <a href="{{ Storage::url($doc->file_path) }}" target="_blank" rel="noopener" class="fe-btn fe-btn--outline fe-btn--sm">Lihat</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-slate-500">Tidak ada berkas yang dipersyaratkan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>

            {{-- ===================== BARIS 4 : UPLOAD BERKAS (FULL, FORM SEJAJAR) ===================== --}}
            {{-- 6. UPLOAD BERKAS --}}
            <article class="fe-card lg:col-span-12" id="upload-berkas">
                <div class="fe-card__header">
                    <h2 class="fe-card__title">Upload Berkas</h2>
                </div>
                <div class="fe-card__body">
                    <form action="{{ route('my.application.documents.store', $data) }}"
                          method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="grid items-start gap-4 sm:grid-cols-2 lg:grid-cols-[1fr_1fr_auto]">
                            <div>
                                <label for="pmb_registration_document_id" class="fe-label">Berkas</label>
                                <select name="pmb_registration_document_id" id="pmb_registration_document_id" class="fe-input">
                                    <option value="">Pilih Opsi</option>
                                    @foreach ($data->pmbRegistrationPath->pmbRegistrationDocument as $req)
                                        <option value="{{ $req->id }}" @selected(old('pmb_registration_document_id') == $req->id)>
                                            {{ $req->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pmb_registration_document_id')
                                    <p class="fe-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="document_file" class="fe-label">File</label>
                                <input
                                    type="file"
                                    class="fe-input"
                                    id="document_file"
                                    name="file"

                                    value="{{ old('file') }}"
                                />
                                <small class="mt-1 block text-xs text-slate-500">Maksimal 2 MB</small>
                                @error('file')
                                    <p class="fe-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="sm:col-span-2 lg:col-span-1 lg:pt-7">
                                <button type="submit" class="fe-btn fe-btn--primary w-full lg:w-auto">
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </article>

        </div>
    </div>
</section>
@endsection

@push('scripts')
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
