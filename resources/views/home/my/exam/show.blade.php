@extends('layouts.app-fe')

@section('title', @$title)

@section('content')
@php
    $answers = $answers ?? [];
    $flags = $flags ?? [];
    $total = count($questionIds);
    $answeredCount = collect($questionIds)->filter(fn ($id) => isset($answers[$id]))->count();
    $isFlagged = in_array($question->id, $flags);
    $selected = $answers[$question->id] ?? null;

    $showUrl = fn (int $n) => route('my.exam.show', [$data, 'no' => $n]);
    $prevUrl = $number > 1 ? $showUrl($number - 1) : null;
    $nextUrl = $number < $total ? $showUrl($number + 1) : null;
@endphp

{{-- Header --}}
{{-- <section class="fe-hero">
    <div class="fe-hero__inner">
        <nav aria-label="Breadcrumb" class="fe-hero__crumb">
            <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a>
            <span class="mx-1">/</span>
            <span aria-current="page">Pendaftaran Saya</span>
        </nav>
        <h1 class="fe-hero__title">Pendaftaran Saya</h1>
        <p class="fe-hero__desc">Pantau jalur, gelombang, tahapan, dan status pendaftaran mahasiswa baru Anda.</p>
    </div>
</section> --}}

<section class="bg-white py-10">
    <div class="mx-auto max-w-6xl px-4">
        @include('components.fe.alert')

        {{-- Bilah atas ujian --}}
        <section class="sticky top-0 z-30 border-b border-brand-100 bg-white/95 backdrop-blur">
            <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-4 px-4 py-3">
                <div class="min-w-0">
                    <p class="text-xs font-semibold uppercase tracking-wide text-brand-600">Ujian Online</p>
                    <h1 class="truncate text-lg font-bold text-slate-900">{{ $exam->title ?? 'Ujian Seleksi Masuk' }}</h1>
                    <p class="truncate text-xs text-slate-500">
                        {{ $data->applicant->name }} &middot; No. {{ $data->registration_number }}
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <div class="hidden text-right sm:block">
                        <p class="text-xs text-slate-500">Terjawab</p>
                        <p class="font-bold text-slate-900"><span id="answeredCount">{{ $answeredCount }}</span> / {{ $total }}</p>
                    </div>
                    <div class="fe-timer" id="timer" role="timer" aria-live="off">
                        <span aria-hidden="true">⏱</span>
                        <span id="timerText">--:--:--</span>
                    </div>
                </div>
            </div>
            <div class="h-1 w-full bg-brand-50">
                <div id="progressBar" class="h-1 bg-brand-600 transition-all" style="width: {{ $total ? round($answeredCount / $total * 100) : 0 }}%"></div>
            </div>
        </section>

        <section class="bg-slate-50 py-8">
            <div class="mx-auto max-w-6xl px-4">

                @include('components.alert')

                <div class="grid gap-6 lg:grid-cols-12">

                    {{-- ===================== SOAL (SATU PER HALAMAN) ===================== --}}
                    <article class="fe-card lg:col-span-8" data-question="{{ $question->id }}">
                        <div class="fe-card__header">
                            <span class="fe-card__number">{{ $number }}</span>
                            <h2 class="fe-card__title">Soal {{ $number }} <span class="text-sm font-medium text-slate-500">dari {{ $total }}</span></h2>
                        </div>

                        <div class="fe-card__body">
                            <div class="text-slate-800">
                                {!! nl2br($question->description) !!}
                                <br>
                                <br>
                                {!! nl2br($question->question) !!}
                            </div>

                            @if (! empty($question->media_path))
                                <img src="{{ $question->media_path }}" alt="Gambar soal {{ $number }}" class="mt-4 max-h-80 rounded-lg border border-brand-100 object-contain">
                            @endif

                            <fieldset class="mt-6 space-y-3">
                                <legend class="sr-only">Pilihan jawaban soal {{ $number }}</legend>
                                @foreach ($question->options as $opt)
                                    <label class="fe-option">
                                        <input type="radio"
                                            class="peer sr-only"
                                            name="answer"
                                            value="{{ $opt->id }}"
                                            @checked($selected == $opt->id)>
                                        <div class="fe-option__box">
                                        @if ($opt->media_path)
                                            <img src="{{ $opt->media_path }}" alt="Gambar opsi {{ $opt->label }}" class="mt-4 max-h-80 rounded-lg border border-brand-100 object-contain">
                                        @else
                                            <span class="fe-option__key">{{ $opt->label }}</span>
                                            <span class="pt-1">{!! nl2br($opt->option) !!}</span>
                                        @endif
                                        </div>
                                    </label>
                                @endforeach
                            </fieldset>
                        </div>

                        {{-- Tombol bawah: Kembali | Ragu-ragu | Selanjutnya --}}
                        <div class="border-t border-brand-100 px-6 py-4">
                            <p class="mb-3 min-h-4 text-right text-xs text-slate-500" id="saveStatus" aria-live="polite"></p>
                            <div class="grid grid-cols-1 items-center gap-3 sm:grid-cols-3">
                                <a @if ($prevUrl) href="{{ $prevUrl }}" data-nav-link @else aria-disabled="true" tabindex="-1" @endif
                                class="fe-btn fe-btn--outline">
                                    ‹ Kembali
                                </a>

                                <button type="button"
                                        id="flagBtn"
                                        class="fe-btn fe-btn--flag"
                                        aria-pressed="{{ $isFlagged ? 'true' : 'false' }}">
                                    <span id="flagLabel">{{ $isFlagged ? '⚑ Ragu-ragu (ditandai)' : '⚐ Ragu-ragu' }}</span>
                                </button>

                                @if ($nextUrl)
                                    <a href="{{ $nextUrl }}" data-nav-link class="fe-btn fe-btn--primary">
                                        Selanjutnya ›
                                    </a>
                                @else
                                    <button type="button" class="fe-btn fe-btn--primary" data-open-finish>
                                        Selesai Ujian
                                    </button>
                                @endif
                            </div>
                        </div>
                    </article>

                    {{-- ===================== DAFTAR SOAL (GRID) ===================== --}}
                    <aside class="space-y-6 lg:col-span-4 lg:sticky lg:top-28 lg:self-start">
                        <div class="fe-card">
                            <div class="fe-card__header">
                                <h2 class="fe-card__title">Daftar Soal</h2>
                            </div>
                            <div class="fe-card__body">
                                <div class="grid grid-cols-5 gap-2 sm:grid-cols-8 lg:grid-cols-5" id="navigator">
                                    @foreach ($questionIds as $qid)
                                        <a href="{{ $showUrl($loop->iteration) }}"
                                        data-nav-link
                                        class="fe-qnav__btn w-full"
                                        data-question-id="{{ $qid }}"
                                        data-answered="{{ isset($answers[$qid]) ? 'true' : 'false' }}"
                                        data-flagged="{{ in_array($qid, $flags) ? 'true' : 'false' }}"
                                        @if ($qid == $question->id) aria-current="true" @endif
                                        aria-label="Soal {{ $loop->iteration }}">
                                            {{ $loop->iteration }}
                                        </a>
                                    @endforeach
                                </div>

                                <ul class="mt-5 grid grid-cols-2 gap-2 text-xs text-slate-600">
                                    <li class="flex items-center gap-2"><span class="h-3 w-3 rounded bg-brand-600"></span> Terjawab</li>
                                    <li class="flex items-center gap-2"><span class="h-3 w-3 rounded bg-amber-400"></span> Ragu-ragu</li>
                                    <li class="flex items-center gap-2"><span class="h-3 w-3 rounded border border-slate-300 bg-white"></span> Belum dijawab</li>
                                    <li class="flex items-center gap-2"><span class="h-3 w-3 rounded ring-2 ring-brand-500"></span> Soal aktif</li>
                                </ul>
                            </div>
                        </div>

                        <button type="button" class="fe-btn fe-btn--outline fe-btn--block" data-open-finish>
                            Selesai Ujian
                        </button>

                        @if (! empty($exam->instructions))
                        <div class="rounded-xl border border-brand-100 bg-white p-5 text-sm text-slate-600">
                            <p class="font-semibold text-slate-900">Petunjuk</p>
                            <div class="mt-2">{!! nl2br(e($exam->instructions)) !!}</div>
                        </div>
                        @endif
                    </aside>
                </div>

                {{-- Dialog konfirmasi selesai --}}
                <dialog id="finishDialog" class="w-full max-w-md rounded-2xl p-0 shadow-xl backdrop:bg-slate-900/50">
                    <form id="finishForm" method="POST" action="{{ route('my.exam.finish', $data) }}" class="p-6">
                        @csrf
                        <h2 class="text-lg font-bold text-slate-900">Akhiri ujian?</h2>
                        <p class="mt-2 text-sm text-slate-600">Jawaban tidak dapat diubah setelah ujian diakhiri.</p>

                        <dl class="mt-4 grid grid-cols-3 gap-3 text-center">
                            <div class="rounded-lg bg-brand-50 p-3">
                                <dt class="text-xs text-slate-500">Terjawab</dt>
                                <dd class="text-xl font-bold text-brand-700" id="sumAnswered">0</dd>
                            </div>
                            <div class="rounded-lg bg-slate-100 p-3">
                                <dt class="text-xs text-slate-500">Kosong</dt>
                                <dd class="text-xl font-bold text-slate-700" id="sumEmpty">0</dd>
                            </div>
                            <div class="rounded-lg bg-amber-50 p-3">
                                <dt class="text-xs text-slate-500">Ragu-ragu</dt>
                                <dd class="text-xl font-bold text-amber-600" id="sumFlagged">0</dd>
                            </div>
                        </dl>

                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" class="fe-btn fe-btn--outline" id="finishCancel">Kembali ke Soal</button>
                            <button type="submit" class="fe-btn fe-btn--primary">Ya, Akhiri Ujian</button>
                        </div>
                    </form>
                </dialog>
            </div>
        </section>
    </div>
</section>

@php
    $examConfig = [
        'answerUrl'  => route('my.exam.answer', $data),
        'endsAt'     => optional($exam->ends_at)->toIso8601String(),
        'serverNow'  => now()->toIso8601String(),
        'total'      => $total,
        'questionId' => $question->id,
        'answered'   => array_map('strval', array_keys($answers)),
        'flags'      => array_map('strval', array_values($flags)),
        'prevUrl'    => $prevUrl,
        'nextUrl'    => $nextUrl,
    ];
@endphp

@push('scripts')
<script>
(function () {
    const cfg = {{ Illuminate\Support\Js::from($examConfig) }};
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content
        || document.querySelector('#finishForm input[name="_token"]').value;

    const qid = String(cfg.questionId);
    const answered = new Set(cfg.answered);
    const flagged = new Set(cfg.flags);
    const statusEl = document.getElementById('saveStatus');
    const flagBtn = document.getElementById('flagBtn');
    let pending = null;      // payload yang belum tersimpan
    let finishing = false;

    // ---------- Penanda & progres ----------
    function refreshMarks() {
        document.querySelectorAll('#navigator [data-question-id]').forEach(function (b) {
            const id = b.dataset.questionId;
            b.dataset.answered = answered.has(id) ? 'true' : 'false';
            b.dataset.flagged = flagged.has(id) ? 'true' : 'false';
        });
        document.getElementById('answeredCount').textContent = answered.size;
        document.getElementById('progressBar').style.width =
            (cfg.total ? Math.round(answered.size / cfg.total * 100) : 0) + '%';
    }

    // ---------- Simpan jawaban otomatis ----------
    function setStatus(text, isError) {
        statusEl.textContent = text;
        statusEl.className = 'mb-3 min-h-4 text-right text-xs ' + (isError ? 'text-red-600' : 'text-slate-500');
    }

    async function flush() {
        const payload = pending;
        if (!payload) return true;
        setStatus('Menyimpan…');
        try {
            const res = await fetch(cfg.answerUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                body: JSON.stringify(payload),
            });
            if (!res.ok) throw new Error('HTTP ' + res.status);
            if (pending === payload) pending = null;
            setStatus('Tersimpan ✓');
            return true;
        } catch (err) {
            console.error(err);
            setStatus('Gagal menyimpan, mencoba lagi…', true);
            return false;
        }
    }

    function queue(patch) {
        const checked = document.querySelector('input[name="answer"]:checked');
        pending = Object.assign({
            question_id: cfg.questionId,
            option_id: checked ? checked.value : null,
            flagged: flagged.has(qid),
        }, patch);
        flush();
    }

    setInterval(function () { if (pending) flush(); }, 5000);

    document.querySelectorAll('input[name="answer"]').forEach(function (input) {
        input.addEventListener('change', function () {
            answered.add(qid);
            refreshMarks();
            queue({ option_id: input.value });
        });
    });

    flagBtn.addEventListener('click', function () {
        const on = !flagged.has(qid);
        on ? flagged.add(qid) : flagged.delete(qid);
        flagBtn.setAttribute('aria-pressed', on ? 'true' : 'false');
        document.getElementById('flagLabel').textContent = on ? '⚑ Ragu-ragu (ditandai)' : '⚐ Ragu-ragu';
        refreshMarks();
        queue({ flagged: on });
    });

    // ---------- Pindah soal: pastikan jawaban tersimpan dulu ----------
    async function go(url) {
        if (!url) return;
        if (pending && !(await flush())) {
            setStatus('Jawaban belum tersimpan. Periksa koneksi lalu coba lagi.', true);
            return;
        }
        window.location.href = url;
    }

    document.querySelectorAll('[data-nav-link]').forEach(function (a) {
        a.addEventListener('click', function (e) {
            e.preventDefault();
            go(a.getAttribute('href'));
        });
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'ArrowRight') go(cfg.nextUrl);
        if (e.key === 'ArrowLeft') go(cfg.prevUrl);
    });

    // ---------- Dialog selesai ----------
    const dialog = document.getElementById('finishDialog');
    const form = document.getElementById('finishForm');

    async function openFinish() {
        if (pending) await flush();
        document.getElementById('sumAnswered').textContent = answered.size;
        document.getElementById('sumEmpty').textContent = cfg.total - answered.size;
        document.getElementById('sumFlagged').textContent = flagged.size;
        dialog.showModal();
    }
    document.querySelectorAll('[data-open-finish]').forEach(b => b.addEventListener('click', openFinish));
    document.getElementById('finishCancel').addEventListener('click', () => dialog.close());
    form.addEventListener('submit', () => { finishing = true; });

    // ---------- Timer (berdasarkan waktu server) ----------
    const timerEl = document.getElementById('timer');
    const timerText = document.getElementById('timerText');
    const offset = Date.parse(cfg.serverNow) - Date.now();
    const endsAt = cfg.endsAt ? Date.parse(cfg.endsAt) : null;

    function pad(n) { return String(n).padStart(2, '0'); }

    function tick() {
        if (!endsAt) { timerText.textContent = '--:--:--'; return; }
        const left = Math.max(0, Math.floor((endsAt - (Date.now() + offset)) / 1000));
        timerText.textContent = pad(Math.floor(left / 3600)) + ':' + pad(Math.floor(left % 3600 / 60)) + ':' + pad(left % 60);
        timerEl.dataset.warning = left <= 300 ? 'true' : 'false';
        if (left === 0 && !finishing) {
            finishing = true;
            clearInterval(timerId);
            form.submit();   // waktu habis: kirim otomatis
        }
    }
    const timerId = setInterval(tick, 1000);
    tick();

    // ---------- Peringatan jika masih ada yang belum tersimpan ----------
    window.addEventListener('beforeunload', function (e) {
        if (!finishing && pending) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

    refreshMarks();
})();
</script>
@endpush
@endsection
