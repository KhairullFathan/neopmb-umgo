@props([
    'totalApplicationGraduate' => 0,
    'totalApplicationReregistration' => 0,
])

@php
    $totalGraduate = (int) $totalApplicationGraduate;
    $totalReregistration = (int) $totalApplicationReregistration;

    // Pastikan jumlah registrasi ulang tidak melebihi jumlah kelulusan.
    $totalReregistration = min($totalReregistration, $totalGraduate);

    $totalNotYetReregistration = max(
        0,
        $totalGraduate - $totalReregistration
    );

    // Hitung persentase dengan proteksi division by zero.
    $percentageReregistration = $totalGraduate > 0
        ? ($totalReregistration / $totalGraduate) * 100
        : 0;

    $percentageNotYetReregistration = $totalGraduate > 0
        ? ($totalNotYetReregistration / $totalGraduate) * 100
        : 0;

    // Format persentase untuk tampilan.
    $percentageReregistration = number_format($percentageReregistration, 2);
    $percentageNotYetReregistration = number_format($percentageNotYetReregistration, 2);
@endphp

<div class="card card--stat">
    <div class="card__body">
        <div class="card__title">Statistik Registrasi Ulang</div>

        <div class="stat">
            <div class="stat__value">{{ $totalGraduate }}</div>
            <div class="stat__meta">
                <span class="stat__label text-eyebrow">Total Pendaftar Lulus</span>
            </div>
        </div>

        <div class="meter meter--block meter--lg meter--stat">
            <div class="meter__track">
                <span
                    class="meter__bar meter__bar--success"
                    style="width: {{ $percentageReregistration }}%"
                ></span>

                <span
                    class="meter__bar meter__bar--warning"
                    style="width: {{ $percentageNotYetReregistration }}%"
                ></span>
            </div>
        </div>

        <div class="stat-detail">
            <div class="stat-detail__col">
                <span class="stat-detail__label text-eyebrow">
                    <span class="indicator indicator--success"></span>
                    Registrasi Ulang
                </span>

                <span class="stat-detail__value">{{ $totalReregistration }}</span>
                <span class="stat-detail__pct">{{ $percentageReregistration }}%</span>
            </div>

            <div class="stat-detail__col">
                <span class="stat-detail__label text-eyebrow">
                    <span class="indicator indicator--warning"></span>
                    Belum Registrasi Ulang
                </span>

                <span class="stat-detail__value">{{ $totalNotYetReregistration }}</span>
                <span class="stat-detail__pct">{{ $percentageNotYetReregistration }}%</span>
            </div>
        </div>
    </div>
</div>
