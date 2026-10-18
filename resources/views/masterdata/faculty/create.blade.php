@extends('layouts.app')

@section('title', @$title)

@section('header')
<div class="page__headline">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb__item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb__item"><a href="{{ route('masterdata.faculty.index') }}">Data Fakultas</a></li>
        <li class="breadcrumb__item" aria-current="page">Tambah</li>
        </ol>
    </nav>
    <h1 class="page__title">Data Fakultas</h1>
    <p class="page__description">
        Tambah data fakultas di Universitas Muhammadiyah Gorontalo
    </p>
</div>
<div class="page__action">
    <a href="{{ route('masterdata.faculty.index') }}" class="button button--danger">
        <x-icon icon="back"/>
        Kembali
    </a>
</div>
@endsection

@section('content')
<section class="page__section">
    @include('components.alert')
    <form class="order-form" method="post" enctype="multipart/form-data" action="{{ route('masterdata.faculty.store') }}">
        @method('POST')
        @csrf
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-12 lg:col-span-8">
                <div class="flex flex-col gap-3">
                    <div class="card">
                        <div class="card__header">
                            <span class="card__title">Fakultas Detail</span>
                        </div>
                        <div class="card__body">
                            <div class="field">
                                <label for="code" class="field__label">Kode Fakultas</label>
                                <input
                                    type="text"
                                    class="input"
                                    id="code"
                                    name="code"
                                    placeholder="FSIK"
                                />
                                @error('code')
                                    <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="field">
                                <label for="name" class="field__label">Nama Fakultas</label>
                                <input
                                    type="text"
                                    class="input"
                                    id="name"
                                    name="name"
                                    placeholder="Fakultas Sains dan Ilmu Komputer"
                                />
                                @error('name')
                                    <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="sticky-bar">
            <div class="me-auto">
                <span class="badge badge--warning badge--soft">
                    <x-icon icon="pencil"/>
                    Draft
                </span>
            </div>
            <a href="{{ route('masterdata.faculty.index') }}" class="button button--ghost button--neutral">
                Batal
            </a>
            <button type="submit" class="button button--primary">
                <x-icon icon="save"/>
                Simpan
            </button>
        </div>
    </form>
</section>
@endsection
@push('scripts')
@endpush
