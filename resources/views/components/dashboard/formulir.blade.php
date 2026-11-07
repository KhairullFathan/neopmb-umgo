@props([
    'totalApplicationPaymentApproved'=>0,
])

<div class="card card--stat">
    <div class="card__body">
        <div class="flex justify-between items-center">
            <span class="icon-box icon-box--primary icon-box--lg"
                ><svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="1em"
                    height="1em"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <g fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="12" r="10" />
                        <path
                            stroke-linecap="round"
                            d="M12 17v1m0-12v1m3 2.5C15 8.12 13.657 7 12 7S9 8.12 9 9.5s1.343 2.5 3 2.5s3 1.12 3 2.5s-1.343 2.5-3 2.5s-3-1.12-3-2.5"
                        />
                    </g>
                </svg>
            </span>
        </div>
        <div class="stat">
            <div class="stat__value"><span class="stat__unit">Rp</span>{{ number_format($totalApplicationPaymentApproved,0,',','.') }}</div>
            <div class="stat__label text-eyebrow">Pembayaran Formulir Masuk</div>
        </div>
    </div>
</div>
