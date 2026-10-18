@extends('layouts.app')

@section('title', @$title)

@section('header')
<div class="page__headline">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb__item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb__item"><a href="{{ route('pmb.wave.index') }}">Gelombang PMB</a></li>
        <li class="breadcrumb__item" aria-current="page">Detail</li>
        </ol>
    </nav>
    <h1 class="page__title">Detail Gelombang Pendaftaran PMB: {{ $wave->name }} {{ $wave->pmbPeriod->name }}</h1>
    <p class="page__description">
        Detail gelombang pendaftaran penerimaan mahasiswa baru di Universitas Muhammadiyah Gorontalo
    </p>
</div>
<div class="page__action">
    <a href="{{ route('pmb.wave.index') }}" class="button button--danger">
        <x-icon icon="back"/>
        Kembali
    </a>
    <a href="{{ route('pmb.wave.path.create', $wave) }}" class="button button--primary">
        <x-icon icon="plus" />
        Tambah
    </a>
</div>
@endsection

@section('content')
<section class="page__section">
    @include('components.alert')
    <div class="card">
        <form method="get" action="">
            <div class="card__header flex-wrap">
                <div class="input-group ms-auto w-full md:w-60 mb-4 md:mb-0" role="search">
                    <span class="input-group__text">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="1em"
                            height="1em"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                        >
                            <g fill="none" stroke="currentColor" stroke-width="1.5">
                            <circle cx="11.5" cy="11.5" r="9.5" />
                            <path stroke-linecap="round" d="M18.5 18.5L22 22" />
                            </g>
                        </svg>
                    </span>
                    <input
                        type="search"
                        name="search"
                        class="input"
                        placeholder="Pencarian..."
                        aria-label="Pencarian"
                        value="{{ request('search') }}"
                    />
                </div>
                <button type="submit" class="button button--primary">
                    <x-icon icon="search"/>
                    Cari
                </button>
                <a href="{{ url()->current() }}" class="button button--danger">
                    <x-icon icon="times"/>
                    Reset
                </a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table--hover table--align-middle">
                <thead class="table__head--alt">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Gelombang</th>
                        <th scope="col">Jalur</th>
                        <th scope="col">Biaya Registrasi</th>
                        <th scope="col">Pilihan Program Studi</th>
                        <th scope="col">Aktif</th>
                        <th scope="col" class="text-end"><span class="sr-only">Aksi</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $row)
                    <tr>
                        <td scope="row">{{ $data->firstItem() + $index }}</td>
                        <th scope="row">{{ $row->pmbWave->name }} - {{ $row->pmbWave->pmbPeriod->name }}</th>
                        <td scope="row">{{ $row->pmbRegistrationPath->code }} - {{ $row->pmbRegistrationPath->name }}</td>
                        <td scope="row">{{ $row->registration_fee_formatted }}</td>
                        <td scope="row">{{ $row->studyPrograms->count() }} Program Studi</td>
                        <td scope="row">
                            <span class="badge badge--soft badge--{{ $row->is_active?'success':'danger' }}">
                                @if ($row->is_active) <x-icon icon="check"/> @else <x-icon icon="times"/> @endif
                                {{ ($row->is_active) ?'Aktif':'Tidak Aktif'}}
                            </span>
                        </td>
                        <td scope="row" class="text-end">
                            <div class="flex justify-end gap-1">
                                <a href="{{ route('pmb.wave.path.show', $row) }}" class="button button--ghost button--primary button--icon-only button--sm">
                                    <x-icon icon="eye" />
                                </a>
                                <a href="{{ route('pmb.wave.path.edit', $row) }}" class="button button--ghost button--secondary button--icon-only button--sm">
                                    <x-icon icon="pencil" />
                                </a>
                                <button
                                    type="button"
                                    class="button button--ghost button--danger button--icon-only button--sm"
                                    data-stisla-dialog-trigger="deleteDialog"
                                    data-delete-name="{{ $row->pmbRegistrationPath->name }}"
                                    data-delete-action="{{ route('pmb.wave.path.destroy', $row) }}"
                                    aria-label="Hapus {{ $row->pmbRegistrationPath->name }}"
                                    data-method="DELETE"
                                >
                                    <x-icon icon="trash"/>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-folder-open text-4xl mb-3 block"></i>
                            <span class="text-sm font-medium">Tidak ada data {{ strtolower(@$title) }} yang ditemukan.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card__footer">
            <x-pagination :paginator="$data"></x-pagination>
        </div>
    </div>
</section>

<x-delete-dialog
    id="deleteDialog"
    title="Hapus Jalur"
    description="Data Jalur Pendaftaran yang dihapus tidak dapat dikembalikan."
    button-text="Hapus Jalur Pendaftaran"
/>
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
