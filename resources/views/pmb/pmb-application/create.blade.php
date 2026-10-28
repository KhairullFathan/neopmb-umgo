@extends('layouts.app')

@section('title', @$title)

@section('header')
<div class="page__headline">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb__item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb__item"><a href="{{ route('pmb.application.index') }}">Data Formulir</a></li>
        <li class="breadcrumb__item" aria-current="page">Tambah</li>
        </ol>
    </nav>
    <h1 class="page__title">Data Formulir Calon Mahasiswa Baru</h1>
    <p class="page__description">
        Tambah data formulir calon mahasiswa baru sistem PMB di Universitas Muhammadiyah Gorontalo
    </p>
</div>
<div class="page__action">
    <a href="{{ route('pmb.application.index') }}" class="button button--danger">
        <x-icon icon="back"/>
        Kembali
    </a>
</div>
@endsection

@section('content')
@php
    $option_school_type = ['SMA','MA','SMK'];
    $school_name_label = 'Asal Sekolah';
    $school_name_placeholder = 'SMA N 1 GORONTALO';
    $school_major_label = 'Jurusan Sekolah';
    $school_major_placeholder = 'IPA atau TKJ';
    $school_type_label = 'Jenis Sekolah';
    if(in_array($pmb_wave_path?->pmbRegistrationPath->code,['PASCA','PROFESI'])){
        $option_school_type = ['NEGERI','SWASTA'];
        $school_name_label = 'Asal Universitas';
        $school_name_placeholder = 'UNIVERSITAS MUHAMMADIYAH GORONTALO';
        $school_major_label = 'Program Studi';
        $school_major_placeholder = 'KEPERAWATAN';
        $school_type_label = 'Jenis Perguruan Tinggi';
    } else if(in_array($pmb_wave_path?->pmbRegistrationPath->code, ['REGKEDOK'])){
        $option_school_type = ['SMA','MA'];
        $school_name_label = 'Asal Sekolah';
        $school_name_placeholder = 'SMA N 1 GORONTALO';
        $school_major_label = 'Jurusan Sekolah';
        $school_major_placeholder = 'IPA';
        $school_type_label = 'Jenis Sekolah';
    }
    else{
        $option_school_type = ['SMA','MA','SMK'];
        $school_name_label = 'Asal Sekolah';
        $school_name_placeholder = 'SMA N 1 GORONTALO';
        $school_major_label = 'Jurusan Sekolah';
        $school_major_placeholder = 'IPA atau TKJ';
        $school_type_label = 'Jenis Sekolah';
    }
    $graduate_years = [];
    for ($year = date('Y') + 1; $year >= date('Y') - 20; $year--) {
        $graduate_years[] = $year;
    }
@endphp
<section class="page__section">
    @include('components.alert')
    <form class="order-form" method="post" enctype="multipart/form-data" action="{{ route('pmb.application.store') }}">
        @method('POST')
        @csrf
        <input type="hidden" name="setuju" value="1">
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-12 lg:col-span-12">
                <div class="flex flex-col gap-3">
                    <div class="card">
                        <div class="card__header">
                            <span class="card__title">Formulir Calon Mahasiswa Baru</span>
                        </div>
                        <div class="card__body">
                            <div class="field">
                                <label for="period_id" class="field__label">Periode PMB</label>
                                <select name="period_id" id="period_id" class="select" onchange="changePeriod(this.value)">
                                    <option value="">Pilih Opsi</option>
                                    @foreach ($periods as $item=>$name)
                                        <option value="{{ $item }}" @selected($item == request('period_id'))>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('period_id')
                                    <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="field">
                                <label for="pmb_wave_id" class="field__label">Gelombang PMB</label>
                                <select name="pmb_wave_id" id="pmb_wave_id" class="select" onchange="changePmbWave(this.value)">
                                    <option value="">Pilih Opsi</option>
                                    @foreach ($pmbWaves as $item)
                                        <option value="{{ $item->id }}" @selected($item->id == request('pmb_wave_id'))>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                @error('pmb_wave_id')
                                    <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="field">
                                <label for="pmb_wave_path_id" class="field__label">Jalur PMB</label>
                                <select name="pmb_wave_path_id" id="pmb_wave_path_id" class="select" onchange="changePmbWavePath(this.value)">
                                    <option value="">Pilih Opsi</option>
                                    @foreach ($pmbWavePaths as $item)
                                        <option value="{{ $item->id }}" @selected($item->id == request('pmb_wave_path_id'))>{{ $item->pmbRegistrationPath->name }}</option>
                                    @endforeach
                                </select>
                                @error('pmb_wave_path_id')
                                    <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="field">
                                <label for="name" class="field__label">Nama lengkap</label>
                                <input
                                    type="text"
                                    class="input"
                                    id="name"
                                    name="name"
                                    placeholder=""
                                    value="{{ old('name') }}"
                                />
                                @error('name')
                                    <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="field">
                                <label for="identity_number" class="field__label">NIK/No Passport bagi mahasiswa internasional</label>
                                <input
                                    type="text"
                                    class="input"
                                    id="identity_number"
                                    name="identity_number"
                                    placeholder=""
                                    value="{{ old('identity_number') }}"
                                />
                                @error('identity_number')
                                    <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="field">
                                <label for="phone" class="field__label">Nomor telepon (WhatsApp aktif)</label>
                                <input
                                    type="text"
                                    class="input"
                                    id="phone"
                                    name="phone"
                                    placeholder=""
                                    value="{{ old('phone') }}"
                                />
                                @error('phone')
                                    <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="field">
                                <label for="school_origin" class="field__label">{{ $school_name_label }}</label>
                                <input
                                    type="text"
                                    class="input"
                                    id="school_origin"
                                    name="school_origin"
                                    placeholder="{{ $school_name_placeholder }}"
                                    value="{{ old('school_origin') }}"
                                />
                                @error('school_origin')
                                    <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="field">
                                <label for="school_type" class="field__label">{{ $school_type_label }}</label>
                                <select name="school_type" id="school_type" class="select">
                                    <option value="">Pilih Opsi</option>
                                    @foreach ($option_school_type as $item)
                                        <option value="{{ $item }}" @selected($item == old('school_type'))>{{ $item }}</option>
                                    @endforeach
                                </select>
                                @error('school_type')
                                    <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="field">
                                <label for="school_major" class="field__label">{{ $school_major_label }}</label>
                                <input
                                    type="text"
                                    class="input"
                                    id="school_major"
                                    name="school_major"
                                    placeholder="{{ $school_major_placeholder }}"
                                    value="{{ old('school_major') }}"
                                />
                                @error('school_major')
                                    <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="field">
                                <label for="graduate_year" class="field__label">Tahun Lulus</label>
                                <select name="graduate_year" id="graduate_year" class="select">
                                    <option value="">Pilih Opsi</option>
                                    @foreach ($graduate_years as $item)
                                        <option value="{{ $item }}" @selected($item == old('graduate_year'))>{{ $item }}</option>
                                    @endforeach
                                </select>
                                @error('graduate_year')
                                    <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="field">
                                <label for="study_programs[]" class="field__label">Pilihan Program Studi 1</label>
                                <select name="study_programs[]" id="study_programs[]" class="select">
                                    <option value="">Pilih Opsi</option>
                                    @forelse ($pmb_wave_path?->studyPrograms as $item)
                                        <option value="{{ $item->code }}" @selected($item->code === old('study_programs.0'))>{{ $item->degree_level }} {{ $item->name }}</option>
                                    @endforelse
                                </select>
                                @error('study_programs.0')
                                    <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="field">
                                <label for="study_programs[]" class="field__label">Pilihan Program Studi 2</label>
                                <select name="study_programs[]" id="study_programs[]" class="select">
                                    <option value="">Pilih Opsi</option>
                                    @forelse ($pmb_wave_path?->studyPrograms as $item)
                                        <option value="{{ $item->code }}" @selected($item->code === old('study_programs.1'))>{{ $item->degree_level }} {{ $item->name }}</option>
                                    @endforelse
                                </select>
                                @error('study_programs.1')
                                    <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 lg:col-span-6">

            </div>
        </div>

        <div class="sticky-bar">
            <div class="me-auto">
                <span class="badge badge--warning badge--soft">
                    <x-icon icon="pencil"/>
                    Draft
                </span>
            </div>
            <a href="{{ route('user.index') }}" class="button button--ghost button--neutral">
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
<script>
    function changePeriod(id){
        const url = new URL(window.location.href);
        if(id){
            url.searchParams.set('period_id', id);
        }else{
            url.searchParams.set('period_id');
        }
        window.location.href= url.toString();
    }
    function changePmbWave(id){
        const url = new URL(window.location.href);
        if(id){
            url.searchParams.set('pmb_wave_id', id);
        }else{
            url.searchParams.set('pmb_wave_id');
        }
        window.location.href= url.toString();
    }
    function changePmbWavePath(id){
        const url = new URL(window.location.href);
        if(id){
            url.searchParams.set('pmb_wave_path_id', id);
        }else{
            url.searchParams.set('pmb_wave_path_id');
        }
        window.location.href= url.toString();
    }
</script>
@endpush
