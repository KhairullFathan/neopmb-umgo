@extends('layouts.app')

@section('title', @$title)

@section('header')
<div class="page__headline">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb__item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb__item"><a href="{{ route('masterdata.study_program.index') }}">Data Program Studi</a></li>
        <li class="breadcrumb__item" aria-current="page">Tambah</li>
        </ol>
    </nav>
    <h1 class="page__title">Data Program Studi</h1>
    <p class="page__description">
        Tambah data program studi di Universitas Muhammadiyah Gorontalo
    </p>
</div>
<div class="page__action">
    <a href="{{ route('masterdata.study_program.index') }}" class="button button--danger">
        <x-icon icon="back"/>
        Kembali
    </a>
</div>
@endsection

@section('content')
<section class="page__section">
    @include('components.alert')
    <form class="order-form" method="post" enctype="multipart/form-data" action="{{ route('masterdata.study_program.store') }}">
        @method('POST')
        @csrf
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-12 lg:col-span-8">
                <div class="flex flex-col gap-3">
                    <div class="card">
                        <div class="card__header">
                            <span class="card__title">Program Studi Detail</span>
                        </div>
                        <div class="card__body">
                            <div class="field">
                                <label for="faculty_code" class="field__label">Fakultas</label>
                                <select name="faculty_code" id="faculty_code" class="select">
                                    <option value="">Pilih Opsi</option>
                                    @foreach ($faculty as $item)
                                    <option value="{{ $item->code }}" {{ old('faculty_code') == $item->code ? 'selected':'' }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                @error('faculty_code')
                                    <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="field">
                                <label for="code" class="field__label">Kode Program Studi</label>
                                <input
                                    type="text"
                                    class="input"
                                    id="code"
                                    name="code"
                                    placeholder="SI"
                                    value="{{ old('code') }}"
                                />
                                @error('code')
                                    <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="field">
                                <label for="name" class="field__label">Nama Program Studi</label>
                                <input
                                    type="text"
                                    class="input"
                                    id="name"
                                    name="name"
                                    placeholder="SISTEM INFORMASI"
                                    value="{{ old('name') }}"
                                />
                                @error('name')
                                    <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="field">
                                <label for="degree_level" class="field__label">Jenjang</label>
                                <select name="degree_level" id="degree_level" class="select">
                                    <option value="">Pilih Opsi</option>
                                    @foreach (['DI','DII','DIII','DIV','S1','S2','S3','PROFESI'] as $item)
                                    <option value="{{ $item }}" {{ old('degree_level') == $item ? 'selected' : '' }}>{{ $item }}</option>
                                    @endforeach
                                </select>
                                @error('degree_level')
                                    <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="field">
                                <label for="accreditation" class="field__label">Akreditas</label>
                                <input
                                    type="text"
                                    class="input"
                                    id="accreditation"
                                    name="accreditation"
                                    placeholder="UNGGUL"
                                    value="{{ old('accreditation') }}"
                                />
                                @error('accreditation')
                                    <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="field">
                                <label for="degree_title" class="field__label">Gelar Lulusan</label>
                                <input
                                    type="text"
                                    class="input"
                                    id="degree_title"
                                    name="degree_title"
                                    placeholder="S.Kom"
                                    value="{{ old('degree_title') }}"
                                />
                                @error('degree_title')
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
                            <span class="card__title">Program Studi Detail</span>
                        </div>
                        <div class="card__body">
                            <div class="field">
                                <label for="description" class="field__label">Deskripsi</label>
                                <textarea name="description" id="description" placeholder="Deskripsi program studi" class="textarea" rows="4">{{ old('description') }}</textarea>
                                @error('description')
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
            <a href="{{ route('masterdata.study_program.index') }}" class="button button--ghost button--neutral">
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
