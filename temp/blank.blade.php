@extends('layouts.app')

@section('title', @$title)

@section('header')
<div class="page__headline">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb__item"><a href="/meridian/">Dashboard</a></li>
        <li class="breadcrumb__item" aria-current="page">Blank</li>
        </ol>
    </nav>
    <h1 class="page__title">Blank page</h1>
    <p class="page__description">
        A clean starting point — duplicate this file and build your view.
    </p>
    </div>
    <div class="page__action">
    <button type="button" class="button button--primary">
        <svg
        xmlns="http://www.w3.org/2000/svg"
        width="1em"
        height="1em"
        viewBox="0 0 24 24"
        aria-hidden="true"
        >
        <g fill="none" stroke="currentColor" stroke-width="1.5">
            <circle cx="12" cy="12" r="10" />
            <path stroke-linecap="round" d="M15 12h-3m0 0H9m3 0V9m0 3v3" />
        </g>
        </svg>
        New
    </button>
    </div>
@endsection

@section('content')
<section class="page__section">
<div class="card">
    <div class="empty-state">
        <span class="empty-state__media">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="1em"
                height="1em"
                viewBox="0 0 24 24"
                aria-hidden="true"
                >
                <g fill="none" stroke="currentColor" stroke-width="1.5">
                    <path
                    d="M5 8c0-2.828 0-4.243.879-5.121C6.757 2 8.172 2 11 2h2c2.828 0 4.243 0 5.121.879C19 3.757 19 5.172 19 8v8c0 2.828 0 4.243-.879 5.121C17.243 22 15.828 22 13 22h-2c-2.828 0-4.243 0-5.121-.879C5 20.243 5 18.828 5 16zm0-3.924c-.975.096-1.631.313-2.121.803C2 5.757 2 7.172 2 10v4c0 2.828 0 4.243.879 5.121c.49.49 1.146.707 2.121.803M19 4.076c.975.096 1.631.313 2.121.803C22 5.757 22 7.172 22 10v4c0 2.828 0 4.243-.879 5.121c-.49.49-1.146.707-2.121.803"
                    />
                    <path stroke-linecap="round" d="M9 13h6M9 9h6m-6 8h3" />
                </g>
            </svg>
        </span>
        <h3 class="empty-state__title">Your content here</h3>
        <p class="empty-state__text">Replace this card with your page content.</p>
    </div>
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
@endpush
