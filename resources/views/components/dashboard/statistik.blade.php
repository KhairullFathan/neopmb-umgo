
@props([
    'totalApplication' => 0,
    'totalApplicationDocumentComplete' => 0,
    'totalApplicationDocumentIncomplete' => 0,
])

@php
    $total = max(0, (int) $totalApplication);
    $totalComplete = max(0, (int) $totalApplicationDocumentComplete);
    $totalIncomplete = max(0, (int) $totalApplicationDocumentIncomplete);

    // Hitung persentase dengan proteksi division by zero.
    $percentageComplete = $total > 0
        ? ($totalComplete / $total) * 100
        : 0;

    $percentageIncomplete = $total > 0
        ? ($totalIncomplete / $total) * 100
        : 0;

    // Format persentase.
    $percentageComplete = number_format($percentageComplete, 2);
    $percentageIncomplete = number_format($percentageIncomplete, 2);
@endphp

<div class="card card--stat">
    <div class="card__body">
        <div class="card__title">Statistik Pendaftar</div>

        <div class="stat">
            <div class="stat__value">{{ $total }}</div>
            <div class="stat__meta">
                <span class="stat__label text-eyebrow">Total Pendaftar</span>
            </div>
        </div>

        <div class="meter meter--block meter--lg meter--stat">
            <div class="meter__track">
                <span
                    class="meter__bar meter__bar--success"
                    style="width: {{ $percentageComplete }}%"
                ></span>

                <span
                    class="meter__bar meter__bar--warning"
                    style="width: {{ $percentageIncomplete }}%"
                ></span>
            </div>
        </div>

        <div class="stat-detail">
            <div class="stat-detail__col">
                <span class="stat-detail__label text-eyebrow">
                    <span class="indicator indicator--success"></span>
                    Lengkap Berkas
                </span>

                <span class="stat-detail__value">{{ $totalComplete }}</span>
                <span class="stat-detail__pct">{{ $percentageComplete }}%</span>
            </div>

            <div class="stat-detail__col">
                <span class="stat-detail__label text-eyebrow">
                    <span class="indicator indicator--warning"></span>
                    Belum Lengkap Berkas
                </span>

                <span class="stat-detail__value">{{ $totalIncomplete }}</span>
                <span class="stat-detail__pct">{{ $percentageIncomplete }}%</span>
            </div>
        </div>
    </div>
</div>
