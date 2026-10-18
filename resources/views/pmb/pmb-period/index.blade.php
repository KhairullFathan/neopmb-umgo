@extends('layouts.app')

@section('title', @$title)

@section('header')
<div class="page__headline">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb__item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb__item" aria-current="page">Periode PMB</li>
        </ol>
    </nav>
    <h1 class="page__title">Data Periode PMB</h1>
    <p class="page__description">
        Daftar periode penerimaan mahasiswa baru di Universitas Muhammadiyah Gorontalo
    </p>
</div>
<div class="page__action">
    <button
        type="button"
        class="button button--primary"
        data-stisla-dialog-trigger="addPmbPeriod"

        data-action="{{ route('pmb.period.store') }}"
        data-method="POST"

        data-id=""
        data-name=""
        data-is_active="0"
    >
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
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12 21C10.22 21 8.47991 20.4722 6.99987 19.4832C5.51983 18.4943 4.36628 17.0887 3.68509 15.4442C3.0039 13.7996 2.82567 11.99 3.17294 10.2442C3.5202 8.49836 4.37737 6.89472 5.63604 5.63604C6.89472 4.37737 8.49836 3.5202 10.2442 3.17294C11.99 2.82567 13.7996 3.0039 15.4442 3.68509C17.0887 4.36628 18.4943 5.51983 19.4832 6.99987C20.4722 8.47991 21 10.22 21 12C21 14.387 20.0518 16.6761 18.364 18.364C16.6761 20.0518 14.387 21 12 21ZM12 4.5C10.5166 4.5 9.0666 4.93987 7.83323 5.76398C6.59986 6.58809 5.63856 7.75943 5.07091 9.12988C4.50325 10.5003 4.35473 12.0083 4.64411 13.4632C4.9335 14.918 5.64781 16.2544 6.6967 17.3033C7.7456 18.3522 9.08197 19.0665 10.5368 19.3559C11.9917 19.6453 13.4997 19.4968 14.8701 18.9291C16.2406 18.3614 17.4119 17.4001 18.236 16.1668C19.0601 14.9334 19.5 13.4834 19.5 12C19.5 10.0109 18.7098 8.10323 17.3033 6.6967C15.8968 5.29018 13.9891 4.5 12 4.5Z" fill="currentColor"></path> <path d="M9.00001 15.75C8.90147 15.7504 8.80383 15.7312 8.71282 15.6934C8.62181 15.6557 8.53926 15.6001 8.47001 15.53C8.32956 15.3893 8.25067 15.1987 8.25067 15C8.25067 14.8012 8.32956 14.6106 8.47001 14.47L14.47 8.46997C14.6122 8.33749 14.8002 8.26537 14.9945 8.26879C15.1888 8.27222 15.3742 8.35093 15.5116 8.48835C15.649 8.62576 15.7278 8.81115 15.7312 9.00545C15.7346 9.19975 15.6625 9.38779 15.53 9.52997L9.53001 15.53C9.46077 15.6001 9.37822 15.6557 9.2872 15.6934C9.19619 15.7312 9.09855 15.7504 9.00001 15.75Z" fill="currentColor"></path> <path d="M15 15.75C14.9015 15.7504 14.8038 15.7312 14.7128 15.6934C14.6218 15.6557 14.5392 15.6001 14.47 15.53L8.47 9.52997C8.33752 9.38779 8.2654 9.19975 8.26882 9.00545C8.27225 8.81115 8.35097 8.62576 8.48838 8.48835C8.62579 8.35093 8.81118 8.27222 9.00548 8.26879C9.19978 8.26537 9.38782 8.33749 9.53 8.46997L15.53 14.47C15.6704 14.6106 15.7493 14.8012 15.7493 15C15.7493 15.1987 15.6704 15.3893 15.53 15.53C15.4608 15.6001 15.3782 15.6557 15.2872 15.6934C15.1962 15.7312 15.0985 15.7504 15 15.75Z" fill="currentColor"></path> </g></svg>
                    Reset
                </a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table--hover table--align-middle">
                <thead class="table__head--alt">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">ID Periode</th>
                        <th scope="col">Nama Periode</th>
                        <th scope="col">Aktif</th>
                        <th scope="col" class="text-end"><span class="sr-only">Aksi</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $row)
                    <tr>
                        <td scope="row">{{ $data->firstItem() + $index }}</td>
                        <th scope="row">{{ $row->id }}</th>
                        <td scope="row">{{ $row->name }}</td>
                        <td scope="row">
                            <span class="badge badge--soft badge--{{ $row->is_active?'success':'danger' }}">
                                @if ($row->is_active) <x-icon icon="check"/> @else <x-icon icon="times"/> @endif
                                {{ ($row->is_active) ?'Aktif':'Tidak Aktif'}}
                            </span>
                        </td>
                        <td scope="row" class="text-end">
                            <div class="flex justify-end gap-1">
                                <button
                                    type="button"
                                    class="button button--secondary"
                                    data-stisla-dialog-trigger="addPmbPeriod"

                                    data-action="{{ route('pmb.period.update', $row) }}"
                                    data-method="PUT"

                                    data-id="{{ $row->id }}"
                                    data-name="{{ $row->name }}"
                                    data-is_active="{{ $row->is_active }}"
                                >
                                    <x-icon icon="pencil"/>
                                </button>
                                <button
                                    type="button"
                                    class="button button--ghost button--danger button--icon-only button--sm"
                                    data-stisla-dialog-trigger="deleteDialog"
                                    data-delete-name="{{ $row->name }}"
                                    data-delete-action="{{ route('pmb.period.destroy', $row) }}"
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
    title="Hapus"
    description="Data periode PMB yang dihapus tidak dapat dikembalikan."
    button-text="Hapus Periode PMB"
/>
<x-form-dialog
    id="addPmbPeriod"
    title="PMB Periode"
    :fields="[
        [
            'name' => 'id',
            'label' => 'ID Periode',
            'type' => 'text',
            'placeholder' => '20261',
            'required' => true,
        ],
        [
            'name' => 'name',
            'label' => 'Nama Periode',
            'type' => 'text',
            'placeholder' => '2026/2027 Ganjil',
            'required' => true,
        ],
        [
            'name' => 'is_active',
            'label' => 'Periode PMB Aktif',
            'type' => 'checkbox',
            'value' => 1,
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
