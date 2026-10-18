@extends('layouts.app')

@section('title', @$title)

@section('header')
<div class="page__headline">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb__item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb__item" aria-current="page">Gelombang PMB</li>
        </ol>
    </nav>
    <h1 class="page__title">Data Gelombang PMB</h1>
    <p class="page__description">
        Daftar gelombang pendaftaran mahasiswa baru di Universitas Muhammadiyah Gorontalo
    </p>
</div>
<div class="page__action">
    <a href="{{ route('pmb.wave.create') }}" class="button button--primary">
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
                <div></div>
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
                <div class="field--inline">
                    <select name="period_id" id="period_id" class="select">
                        <option value="">Pilih Periode PMB</option>
                        @foreach ($periods as $item=>$name)
                        <option value="{{ $item }}" {{ old('period_id') == $item ? 'selected':'' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="button button--primary">
                    <x-icon icon="search" />
                    Cari
                </button>
                <a href="{{ url()->current() }}" class="button button--danger">
                    <x-icon icon="times" />
                    Reset
                </a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table--hover table--align-middle">
                <thead class="table__head--alt">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Periode</th>
                        <th scope="col">Kode</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Jalur Buka</th>
                        <th scope="col" class="text-center">Mulai<br>Selesai</th>
                        <th scope="col">Aktif</th>
                        <th scope="col">Tampil</th>
                        <th scope="col" class="text-end"><span class="sr-only">Aksi</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $row)
                    <tr>
                        <td scope="row">{{ $data->firstItem() + $index }}</td>
                        <th scope="row">({{ $row->pmb_period_id }}) {{ $row->pmbPeriod->name }}</th>
                        <th scope="row">{{ $row->code }}</th>
                        <td scope="row">{{ $row->name }}</td>
                        <td scope="row">{{ $row->pmbWavePath->count() }} Jalur</td>
                        <td scope="row" class="text-center">
                            {{ \Carbon\Carbon::parse($row->registration_start)->format('d-m-Y H:i:s')}}
                            <br/>
                            sd.
                            <br/>
                            {{ \Carbon\Carbon::parse($row->registration_end)->format('d-m-Y H:i:s')}}
                        </td>
                        <td scope="row">
                            <span class="badge badge--soft badge--{{ $row->is_active?'success':'danger' }}">
                                @if ($row->is_active) <x-icon icon="check"/> @else <x-icon icon="times"/> @endif
                                {{ ($row->is_active) ?'Aktif':'Tidak Aktif'}}
                            </span>
                        </td>
                        <td scope="row">
                            <span class="badge badge--soft badge--{{ $row->is_show?'success':'danger' }}">
                                @if ($row->is_show) <x-icon icon="check"/> @else <x-icon icon="times"/> @endif
                                {{ ($row->is_show) ?'Tampil':'Tidak Tampil'}}
                            </span>
                        </td>
                        <td scope="row" class="text-end">
                            <div class="flex justify-end gap-1">
                                <a
                                    href="{{ route('pmb.wave.show', $row) }}"
                                    class="button button--ghost button--primary button--icon-only button--sm"
                                    aria-label="Detail"
                                >
                                    <x-icon icon="eye"/>
                                </a>
                                <a
                                    href="{{ route('pmb.wave.edit', $row) }}"
                                    class="button button--ghost button--neutral button--icon-only button--sm"
                                    aria-label="Edit"
                                >
                                    <x-icon icon="pencil"/>
                                </a>
                                <button
                                    type="button"
                                    class="button button--ghost button--danger button--icon-only button--sm"
                                    data-stisla-dialog-trigger="deleteDialog"
                                    data-delete-name="{{ $row->name }}"
                                    data-delete-action="{{ route('pmb.wave.destroy', $row) }}"
                                    aria-label="Hapus {{ $row->name }}"
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
    title="Hapus Gelombang"
    description="Data gelombang PMB yang dihapus tidak dapat dikembalikan."
    button-text="Hapus Gelombang PMB"
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
