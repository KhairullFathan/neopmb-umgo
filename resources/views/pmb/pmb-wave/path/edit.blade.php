@extends('layouts.app')

@section('title', @$title)

@section('header')
<div class="page__headline">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb__item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb__item"><a href="{{ route('pmb.wave.index') }}">Gelombang PMB</a></li>
        <li class="breadcrumb__item"><a href="{{ route('pmb.wave.show', $wave) }}">Detail Gelombang PMB</a></li>
        <li class="breadcrumb__item" aria-current="page">Edit</li>
        </ol>
    </nav>
    <h1 class="page__title">Edit Data Jalur Gelombang Pendaftaran</h1>
    <p class="page__description">
        Edit data jalur gelombang pendaftaran di Universitas Muhammadiyah Gorontalo
    </p>
</div>
<div class="page__action">
    <a href="{{ route('pmb.wave.show', $wave) }}" class="button button--danger">
        <x-icon icon="back"/>
        Kembali
    </a>
</div>
@endsection

@section('content')
<section class="page__section">
    @include('components.alert')
    <form class="order-form" method="post" enctype="multipart/form-data" action="{{ route('pmb.wave.path.update', $data) }}">
        @method('PUT')
        @csrf
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-12 lg:col-span-8">
                <div class="flex flex-col gap-3">
                    <div class="card">
                        <div class="card__header">
                            <span class="card__title">Pilihan Program Studi</span>
                        </div>
                        <div class="card__body">
                        @foreach ($faculty as $item)
                        <div class="card">
                            <div class="card__header">
                                <span class="card__title">{{ $item->name }} | {{ $item->studyPrograms->count() }} Program Studi</span>
                            </div>
                            <div class="card__body">
                                <div class="field field--inline w-auto">
                                    @foreach ($item->studyPrograms as $item_ps)
                                    <div class="field__item">
                                        <input name="study_program_code[]" {{ in_array($item_ps->code, $data->studyPrograms->pluck('code')->toArray()) ? 'checked':'' }} value="{{ $item_ps->code }}" class="checkbox" type="checkbox" id="{{'checkbox-'.$item_ps->code}}" />
                                        <label class="field__label" for="{{'checkbox-'.$item_ps->code}}">{{ $item_ps->degree_level }} - {{ $item_ps->name }} ({{ $item_ps->code }})</label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 lg:col-span-4">
                <div class="flex flex-col gap-3">
                    <div class="card">
                        <div class="card__header">
                            <span class="card__title">Jalur Gelombang PMB Detail</span>
                        </div>
                        <div class="card__body">
                            <input type="hidden" name="pmb_wave_id" value="{{ $wave->id }}">
                            <div class="field">
                                <label for="pmb_registration_path_id" class="field__label">Periode</label>
                                <select name="pmb_registration_path_id" id="pmb_registration_path_id" class="select">
                                    <option value="">Jalur Pendaftaran</option>
                                    @foreach ($registration_paths as $item=>$name)
                                    <option value="{{ $item }}" {{ old('pmb_registration_path_id', $data->pmb_registration_path_id) == $item ? 'selected':'' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('pmb_registration_path_id')
                                    <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="field">
                                <label for="registration_fee" class="field__label">Biaya Formulir</label>
                                <input
                                    type="text"
                                    class="input currency"
                                    id="registration_fee"
                                    name="registration_fee"
                                    placeholder="Biaya Formulir"
                                    value="{{ old('registration_fee', $data->registration_fee_formatted) }}"
                                />
                                @error('registration_fee')
                                    <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="field">
                                <div class="field__item">
                                    <input name="is_active" value="1" class="checkbox" type="checkbox" id="is_active"  {{ old('is_active', $data->is_active)== 1 ?'checked':'' }}/>
                                    <label class="field__label" for="is_active">Aktif</label>
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
{{-- <script src="/assets/js/order-form.js"></script> --}}
{{-- <script src="/assets/js/pmb.wave.path.form.js"></script> --}}
<script src="/assets/js/table-select.js"></script>
@endpush
