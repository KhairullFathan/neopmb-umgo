@extends('layouts.app')

@section('title', @$title)

@section('header')
<div class="page__headline">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb__item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb__item" aria-current="page">Jalur Pendaftaran</li>
        </ol>
    </nav>
    <h1 class="page__title">Data Jalur Pendaftaran PMB</h1>
    <p class="page__description">
        Daftar jalur pendaftaran penerimaan mahasiswa baru di Universitas Muhammadiyah Gorontalo
    </p>
</div>
<div class="page__action">
    <button
        type="button"
        class="button button--primary"
        data-stisla-dialog-trigger="formDialog"

        data-action="{{ route('pmb.registration_path.store') }}"
        data-method="POST"

        data-code=""
        data-name=""
        data-description=""
    >
        <x-icon icon="plus"/>
        Tambah
    </button>
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
                        <th scope="col">Kode</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Deskripsi</th>
                        <th scope="col" class="text-end"><span class="sr-only">Aksi</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $row)
                    <tr>
                        <td scope="row">{{ $data->firstItem() + $index }}</td>
                        <th scope="row">{{ $row->code }}</th>
                        <td scope="row">{{ $row->name }}</td>
                        <td scope="row">{{ $row->description }}</td>
                        <td scope="row" class="text-end">
                            <div class="flex justify-end gap-1">
                                <a
                                    href="{{ route('pmb.registration_path.show', $row) }}"
                                    class="button button--ghost button--primary button--icon-only button--sm"
                                    aria-label="Detail"
                                >
                                    <x-icon icon="eye"/>
                                </a>
                                <button
                                    type="button"
                                    class="button button--secondary"
                                    data-stisla-dialog-trigger="formDialog"

                                    data-action="{{ route('pmb.registration_path.update', $row) }}"
                                    data-method="PUT"

                                    data-code="{{ $row->code }}"
                                    data-name="{{ $row->name }}"
                                    data-description="{{ $row->description }}"
                                >
                                    <x-icon icon="pencil"/>
                                </button>
                                <button
                                    type="button"
                                    class="button button--ghost button--danger button--icon-only button--sm"
                                    data-stisla-dialog-trigger="deleteDialog"
                                    data-delete-name="{{ $row->name }}"
                                    data-delete-action="{{ route('pmb.registration_path.destroy', $row) }}"
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
    title="Hapus Jalur"
    description="Data Jalur Pendaftaran yang dihapus tidak dapat dikembalikan."
    button-text="Hapus Jalur Pendaftaran"
/>
<x-form-dialog
    id="formDialog"
    title="Jalur Pendaftaran"
    :fields="[
        [
            'name' => 'code',
            'label' => 'Kode Jalur Pendaftaran',
            'type' => 'text',
            'placeholder' => 'REG',
            'required' => true,
        ],
        [
            'name' => 'name',
            'label' => 'Nama Jalur Pendaftaran',
            'type' => 'text',
            'placeholder' => 'REGULER',
            'required' => true,
        ],
        [
            'name' => 'description',
            'label' => 'Deskripsi Jalur Pendaftaran',
            'type' => 'textarea',
            'placeholder' => 'Jalur Pendaftaran Reguler',
            'required' => false,
        ],
    ]"
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-stisla-dialog-trigger]')
        .forEach(function (button) {
            button.addEventListener('click', function () {
                const dialogId = button.dataset.stislaDialogTrigger;
                const dialog = document.getElementById(dialogId);
                if (!dialog) {
                    return;
                }
                const form = dialog.querySelector('form');
                /*
                |--------------------------------------------------------------------------
                | Set action
                |--------------------------------------------------------------------------
                */

                if (button.dataset.action) {
                    form.action = button.dataset.action;
                }
                /*
                |--------------------------------------------------------------------------
                | Set method
                |--------------------------------------------------------------------------
                */
                const methodInput = form.querySelector('input[name="_method"]');
                if (methodInput) {
                    methodInput.value =
                        button.dataset.method || 'POST';
                }
                /*
                |--------------------------------------------------------------------------
                | Isi field
                |--------------------------------------------------------------------------
                */
                dialog.querySelectorAll('[name]')
                    .forEach(function (input) {
                        const name = input.name;
                        // Abaikan CSRF dan method
                        if (
                            name === '_token' ||
                            name === '_method'
                        ) {
                            return;
                        }
                        const value = button.dataset[name];
                        if (value === undefined) {
                            return;
                        }
                        if (input.type === 'checkbox') {
                            input.checked =
                                value === '1' ||
                                value === 'true';
                        } else if (input.type === 'radio') {
                            input.checked =
                                input.value === value;
                        } else {
                            input.value = value;
                        }
                    });
            });
        });
});
</script>
@endpush
