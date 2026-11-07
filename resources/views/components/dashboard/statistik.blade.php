@props([
    'totalApplication'=>0,
    'totalApplicationDocumentComplete'=>0,
    'totalApplicationDocumentIncomplete'=>0
])

@php
    $percentageApplicationDocumentComplete = 0;
    if($totalApplicationDocumentComplete > 0){
        $percentageApplicationDocumentComplete = number_format(($totalApplicationDocumentComplete / $totalApplication) * 100, 2);
    }
    $percentageApplicationDocumentIncomplete = 0;
    if($totalApplicationDocumentIncomplete > 0){
        $percentageApplicationDocumentIncomplete = number_format(($totalApplicationDocumentIncomplete / $totalApplication) * 100, 2);
    }
@endphp

<div class="card card--stat">
    <div class="card__body">
        <div class="card__title">Statistik Pendaftar</div>

        <div class="stat">
            <div class="stat__value">{{ $totalApplication }}</div>
            <div class="stat__meta">
                <span class="stat__label text-eyebrow">Total Pendaftar</span>
            </div>
        </div>

        <div class="meter meter--block meter--lg meter--stat">
            <div class="meter__track">
                <span class="meter__bar meter__bar--success" style="width: {{ $percentageApplicationDocumentComplete }}%"></span>
                <span class="meter__bar meter__bar--warning" style="width: {{ $percentageApplicationDocumentIncomplete }}%"></span>
            </div>
        </div>

        <div class="stat-detail">
            <div class="stat-detail__col">
                <span class="stat-detail__label text-eyebrow">
                    <span class="indicator indicator--success"></span>
                    Lengkap Berkas
                </span>
                <span class="stat-detail__value">{{ $totalApplicationDocumentComplete }}</span>
                <span class="stat-detail__pct">{{ $percentageApplicationDocumentComplete }}%</span>
            </div>

            <div class="stat-detail__col">
                <span class="stat-detail__label text-eyebrow">
                    <span class="indicator indicator--warning"></span>
                    Belum Lengkap Berkas
                </span>
                <span class="stat-detail__value">{{ $totalApplicationDocumentIncomplete }}</span>
                <span class="stat-detail__pct">{{ $percentageApplicationDocumentIncomplete }}%</span>
            </div>
        </div>
    </div>
</div>
