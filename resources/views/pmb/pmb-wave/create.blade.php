@extends('layouts.app')

@section('title', @$title)

@section('header')
<div class="page__headline">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb__item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb__item"><a href="{{ route('pmb.wave.index') }}">Data Gelombang Pendaftaran</a></li>
        <li class="breadcrumb__item" aria-current="page">Tambah</li>
        </ol>
    </nav>
    <h1 class="page__title">Data Gelombang Pendaftaran</h1>
    <p class="page__description">
        Tambah data gelombang pendaftaran di Universitas Muhammadiyah Gorontalo
    </p>
</div>
<div class="page__action">
    <a href="{{ route('pmb.wave.index') }}" class="button button--danger">
        <x-icon icon="back"/>
        Kembali
    </a>
</div>
@endsection

@section('content')
<section class="page__section">
    @include('components.alert')
    <form class="order-form" method="post" enctype="multipart/form-data" action="{{ route('pmb.wave.store') }}">
        @method('POST')
        @csrf
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-12 lg:col-span-8">
                <div class="flex flex-col gap-3">
                    <div class="card">
                        <div class="card__header">
                            <span class="card__title">Gelombang PMB Detail</span>
                        </div>
                        <div class="card__body">
                            <div class="field">
                                <label for="pmb_period_id" class="field__label">Periode</label>
                                <select name="pmb_period_id" id="pmb_period_id" class="select">
                                    <option value="">Pilih Opsi</option>
                                    @foreach ($periods as $item=>$name)
                                    <option value="{{ $item }}" {{ old('pmb_period_id') == $item ? 'selected':'' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('pmb_period_id')
                                    <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="field">
                                <label for="code" class="field__label">Kode Gelombang</label>
                                <input
                                    type="text"
                                    class="input"
                                    id="code"
                                    name="code"
                                    placeholder="GEL-1"
                                    value="{{ old('code') }}"
                                />
                                @error('code')
                                    <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="field">
                                <label for="name" class="field__label">Nama Gelombang</label>
                                <input
                                    type="text"
                                    class="input"
                                    id="name"
                                    name="name"
                                    placeholder="GELOMBANG 1"
                                    value="{{ old('name') }}"
                                />
                                @error('name')
                                    <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="field">
                                <label for="registration_start" class="field__label">Tanggal Mulai</label>
                                <input
                                    type="datetime-local"
                                    class="input"
                                    id="registration_start"
                                    name="registration_start"
                                    placeholder="UNGGUL"
                                    value="{{ old('registration_start') }}"
                                />
                                @error('registration_start')
                                    <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="field">
                                <label for="registration_end" class="field__label">Tanggal Selesai</label>
                                <input
                                    type="datetime-local"
                                    class="input"
                                    id="registration_end"
                                    name="registration_end"
                                    placeholder="UNGGUL"
                                    value="{{ old('registration_end') }}"
                                />
                                @error('registration_end')
                                    <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 lg:col-span-4">
                <div class="flex flex-col gap-3">
                    <div class="card">
                        <div class="card__header">
                            <span class="card__title">Gelombang PMB Detail</span>
                        </div>
                        <div class="card__body">
                            <div class="field">
                                <div class="field__item">
                                    <input name="is_active" value="1" class="checkbox" type="checkbox" id="is_active"  {{ old('is_active')== 1 ?'checked':'' }}/>
                                    <label class="field__label" for="is_active">Aktif</label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="field__item">
                                    <input name="is_show" value="1" class="checkbox" type="checkbox" id="is_show" {{ old('is_show')== 1 ?'checked':'' }}/>
                                    <label class="field__label" for="is_show">Tampil</label>
                                </div>
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
            <a href="{{ route('pmb.wave.index') }}" class="button button--ghost button--neutral">
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
<script
    type="module"
    src="https://cdn.jsdelivr.net/npm/@stisla/vanilla@3/dist/stisla.js"
></script>
<script src="/assets/js/app-shell.js"></script>
<script src="/assets/js/theme.js"></script>
<script src="/assets/js/order-form.js"></script>
<script src="/assets/js/table-select.js"></script>
@endpush
