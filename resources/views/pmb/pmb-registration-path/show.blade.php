@extends('layouts.app')

@section('title', @$title)

@section('header')
<div class="page__headline">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb__item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb__item"><a href="{{ route('pmb.registration_path.index') }}">Jalur Pendaftaran</a></li>
        <li class="breadcrumb__item" aria-current="page">Detail</li>
        </ol>
    </nav>
    <h1 class="page__title">Detail Jalur Pendaftaran PMB</h1>
    <p class="page__description">
        Daftar berkas jalur pendaftaran penerimaan mahasiswa baru di Universitas Muhammadiyah Gorontalo
    </p>
</div>
<div class="page__action">
    <a href="{{ route('pmb.registration_path.index') }}" class="button button--danger">
        <x-icon icon="back"/>
        Kembali
    </a>
    <button
        type="button"
        class="button button--primary"
        data-stisla-dialog-trigger="formDialog"

        data-action="{{ route('pmb.registration_path.document.store') }}"
        data-method="POST"

        data-pmb_registration_path_id="{{ $data->id }}"
        data-name=""
        data-description=""
        data-is_required=""
        data-allowed_mimes_image=""
        data-allowed_mimes_pdf=""
        data-is_active=""
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
                        <th scope="col">Nama</th>
                        <th scope="col">Deskripsi</th>
                        <th scope="col">Tipe File</th>
                        <th scope="col">Berkas Wajib</th>
                        <th scope="col">Berkas Aktif</th>
                        <th scope="col" class="text-end"><span class="sr-only">Aksi</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data->pmbRegistrationDocument as $index => $row)
                    <tr>
                        <td scope="row">{{ $loop->iteration }}</td>
                        <td scope="row">{{ $row->name }}</td>
                        <td scope="row">{{ $row->description }}</td>
                        <td scope="row">{{ $row->allowed_mimes_label }}</td>
                        <td scope="row">
                            <span class="badge badge--soft badge--{{ $row->is_required?'success':'danger' }}">
                                @if ($row->is_required) <x-icon icon="check"/> @else <x-icon icon="times"/> @endif
                                {{ ($row->is_required) ?'Wajib':'Tidak Wajib'}}
                            </span>
                        </td>
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
                                    data-stisla-dialog-trigger="formDialog"

                                    data-action="{{ route('pmb.registration_path.document.update', $row) }}"
                                    data-method="PUT"

                                    data-pmb_registration_path_id="{{ $row->pmb_registration_path_id }}"
                                    data-name="{{ $row->name }}"
                                    data-description="{{ $row->description }}"
                                    data-is_required="{{ $row->is_required }}"
                                    data-is_active="{{ $row->is_active }}"
                                >
                                    <x-icon icon="pencil"/>
                                </button>
                                <button
                                    type="button"
                                    class="button button--ghost button--danger button--icon-only button--sm"
                                    data-stisla-dialog-trigger="deleteDialog"
                                    data-delete-name="{{ $row->name }}"
                                    data-delete-action="{{ route('pmb.registration_path.document.destroy', $row) }}"
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
            'name' => 'pmb_registration_path_id',
            'label' => '',
            'type' => 'hidden',
            'placeholder' => '',
            'required' => true,
        ],
        [
            'name' => 'name',
            'label' => 'Nama Berkas',
            'type' => 'text',
            'placeholder' => 'Ijazah',
            'required' => true,
        ],
        [
            'name' => 'description',
            'label' => 'Deskripsi Berkas Pendaftaran',
            'type' => 'textarea',
            'placeholder' => 'Scan ijazah terakhir',
            'required' => false,
        ],
        [
            'name' => 'is_required',
            'label' => 'Berkas Pendaftaran Wajib',
            'type' => 'checkbox',
            'value' => 1,
        ],
        [
            'name' => 'allowed_mimes[image]',
            'label' => 'Tipe berkas gambar',
            'type' => 'checkbox',
            'value' => 1,
        ],
        [
            'name' => 'allowed_mimes[pdf]',
            'label' => 'Tipe berkas pdf',
            'type' => 'checkbox',
            'value' => 1,
        ],
        [
            'name' => 'is_active',
            'label' => 'Berkas Pendaftaran Aktif',
            'type' => 'checkbox',
            'value' => 1,
        ],
    ]"
/>
@endsection
@push('scripts')
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
