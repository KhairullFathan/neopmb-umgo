@extends('layouts.app')

@section('title', @$title)

@section('header')
<div class="page__headline">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb__item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb__item"><a href="{{ route('user.index') }}">Data Pengguna</a></li>
        <li class="breadcrumb__item" aria-current="page">Tambah</li>
        </ol>
    </nav>
    <h1 class="page__title">Data Pengguna</h1>
    <p class="page__description">
        Tambah data pengguna sistem PMB di Universitas Muhammadiyah Gorontalo
    </p>
</div>
<div class="page__action">
    <a href="{{ route('user.index') }}" class="button button--danger">
        <x-icon icon="back"/>
        Kembali
    </a>
</div>
@endsection

@section('content')
<section class="page__section">
    @include('components.alert')
    <form class="order-form" method="post" enctype="multipart/form-data" action="{{ route('user.store') }}">
        @method('POST')
        @csrf
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-12 lg:col-span-6">
                <div class="flex flex-col gap-3">
                    <div class="card">
                        <div class="card__header">
                            <span class="card__title">Pengguna Detail</span>
                        </div>
                        <div class="card__body">
                            <div class="field">
                                <label for="role" class="field__label">Peran</label>
                                <select name="role" id="role" class="select">
                                    <option value="">Pilih Opsi</option>
                                    @foreach ($roles as $item)
                                    <option value="{{ $item->name }}" {{ old('role') == $item->name ? 'selected':'' }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="field">
                                <label for="username" class="field__label">Username</label>
                                <input
                                    type="text"
                                    class="input"
                                    id="username"
                                    name="username"
                                    placeholder="johndoe"
                                    value="{{ old('username') }}"
                                />
                                @error('username')
                                    <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="field">
                                <label for="email" class="field__label">Email</label>
                                <input
                                    type="text"
                                    class="input"
                                    id="email"
                                    name="email"
                                    placeholder="johndoe@mail.com"
                                    value="{{ old('email') }}"
                                />
                                @error('email')
                                    <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="field">
                                <label for="password" class="field__label">Password</label>
                                <input
                                    type="password"
                                    class="input"
                                    id="password"
                                    name="password"
                                    placeholder="********"
                                    value="{{ old('password') }}"
                                />
                                @error('password')
                                    <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="field">
                                <label for="cpassword" class="field__label">Konfirmasi Password</label>
                                <input
                                    type="password"
                                    class="input"
                                    id="cpassword"
                                    name="cpassword"
                                    placeholder="********"
                                    value="{{ old('cpassword') }}"
                                />
                                @error('cpassword')
                                    <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 lg:col-span-6">
                <div class="flex flex-col gap-3">
                    <h3>Akses Program Studi</h3>
                    @foreach ($faculty as $item)
                    <div class="card">
                        <div class="card__header">
                            <span class="card__title">{{ $item->name }} | {{ $item->studyPrograms->count() }} Program Studi</span>
                        </div>
                        <div class="card__body">
                            <div class="field field--inline w-auto">
                                @foreach ($item->studyPrograms as $item_ps)
                                <div class="field__item">
                                    <input name="study_program_code[]" value="{{ $item_ps->code }}" class="checkbox" type="checkbox" id="{{'checkbox-'.$item_ps->code}}" />
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
<script
    type="module"
    src="https://cdn.jsdelivr.net/npm/@stisla/vanilla@3/dist/stisla.js"
></script>
<script src="/assets/js/app-shell.js"></script>
<script src="/assets/js/theme.js"></script>
<script src="/assets/js/order-form.js"></script>
<script src="/assets/js/table-select.js"></script>
@endpush
