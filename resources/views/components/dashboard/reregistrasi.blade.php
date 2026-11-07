@props([
    'totalApplicationGraduate'=>0,
    'totalApplicationReregistration'=>0,
])

@php
    $percentageApplicationGraduate = 0;
    if($totalApplicationGraduate > 0){
        $percentageApplicationGraduate = number_format(($totalApplicationGraduate / $totalApplicationGraduate) * 100, 2);
    }
    $percentageApplicationReregistration = 0;
    if($totalApplicationReregistration > 0){
        $percentageApplicationReregistration = number_format(($totalApplicationReregistration / $totalApplicationGraduate) * 100, 2);
    }
    $totalApplicationNotYetReregistration = $totalApplicationGraduate-$totalApplicationReregistration;
    $percentageApplicationNotYetReregistration = 0;
    if($totalApplicationNotYetReregistration > 0){
        $percentageApplicationNotYetReregistration = number_format(($totalApplicationNotYetReregistration / $totalApplicationGraduate) * 100, 2);
    }
@endphp

<div class="card card--stat">
    <div class="card__body">
        <div class="card__title">Statistik Registrasi Ulang</div>

        <div class="stat">
            <div class="stat__value">{{ $totalApplicationGraduate }}</div>
            <div class="stat__meta">
                <span class="stat__label text-eyebrow">Total Pendaftar Lulus</span>
            </div>
        </div>

        <div class="meter meter--block meter--lg meter--stat">
            <div class="meter__track">
                <span class="meter__bar meter__bar--success" style="width: {{ $percentageApplicationReregistration }}%"></span>
                <span class="meter__bar meter__bar--warning" style="width: {{ $percentageApplicationGraduate }}%"></span>
            </div>
        </div>

        <div class="stat-detail">
            <div class="stat-detail__col">
                <span class="stat-detail__label text-eyebrow">
                    <span class="indicator indicator--success"></span>
                    Registrasi Ulang
                </span>
                <span class="stat-detail__value">{{ $totalApplicationReregistration }}</span>
                <span class="stat-detail__pct">{{ $percentageApplicationReregistration }}%</span>
            </div>

            <div class="stat-detail__col">
                <span class="stat-detail__label text-eyebrow">
                    <span class="indicator indicator--warning"></span>
                    Belum Registrasi Ulang
                </span>
                <span class="stat-detail__value">{{ $totalApplicationNotYetReregistration }}</span>
                <span class="stat-detail__pct">{{ $percentageApplicationNotYetReregistration }}%</span>
            </div>
        </div>
    </div>
</div>
