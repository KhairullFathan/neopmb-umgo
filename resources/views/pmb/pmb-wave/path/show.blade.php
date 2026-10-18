@extends('layouts.app')

@section('title', @$title)

@section('header')
<div class="page__headline">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb__item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb__item"><a href="{{ route('pmb.wave.index') }}">Gelombang PMB</a></li>
        <li class="breadcrumb__item"><a href="{{ route('pmb.wave.show', $wave) }}">Detail Gelombang PMB</a></li>
        <li class="breadcrumb__item" aria-current="page">Detail</li>
        </ol>
    </nav>
    <h1 class="page__title">Detail Tahapan {{ $wave->name }} {{ $wave->pmbPeriod->name }} Jalur {{ $wave_path->pmbRegistrationPath->name }} ({{ $wave_path->pmbRegistrationPath->code }})</h1>
    <p class="page__description">
        Tambah data jalur gelombang pendaftaran di Universitas Muhammadiyah Gorontalo
    </p>
</div>
<div class="page__action">
    <a href="{{ route('pmb.wave.show', $wave) }}" class="button button--danger">
        <x-icon icon="back"/>
        Kembali
    </a>
    <button
        type="button"
        class="button button--primary"
        data-stisla-dialog-trigger="formDialog"

        data-action="{{ route('pmb.wave.path.stage.store', $wave_path) }}"
        data-method="POST"

        data-pmb_wave_path_id="{{ $wave_path->id }}"
        data-pmb_registration_stage_id=""
        data-sequence="{{ $next_sequence }}"
        data-is_required="1"
        data-execution_type="0"
        data-start_at="0"
        data-end_at="0"
        data-is_active="1"
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

        <div class="table-responsive">
            <table class="table table--hover table--align-middle">
                <thead class="table__head--alt">
                    <tr>
                        <th scope="col">Urutan</th>
                        <th scope="col">Stage</th>
                        <th scope="col">Wajib</th>
                        <th scope="col">Tipe Jadwal</th>
                        <th scope="col">Mulai</th>
                        <th scope="col">Selesai</th>
                        <th scope="col">Aktif</th>
                        <th scope="col" class="text-end"><span class="sr-only">Aksi</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $row)
                    <tr>
                        <th scope="row">{{ $row->sequence }}</th>
                        <th scope="row">{{ $row->pmbRegistrationStage->name }}</th>
                        <td scope="row">
                            <span class="badge badge--soft badge--{{ $row->is_required?'success':'danger' }}">
                                @if ($row->is_required) <x-icon icon="check"/> @else <x-icon icon="times"/> @endif
                                {{ ($row->is_required) ?'Wajib':'Tidak Wajib'}}
                            </span>
                        </td>
                        <td scope="row">{{ $row->execution_type }}</td>
                        <td scope="row">{{ ($row->start_at) ? \Carbon\Carbon::parse($row->start_at)->format('d-m-Y H:i:s'): '-' }}</td>
                        <td scope="row">{{ ($row->end_at) ? \Carbon\Carbon::parse($row->end_at)->format('d-m-Y H:i:s'): '-' }}</td>
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

                                    data-action="{{ route('pmb.wave.path.stage.update', $row) }}"
                                    data-method="PUT"

                                    data-pmb_wave_path_id="{{ $row->pmb_wave_path_id }}"
                                    data-pmb_registration_stage_id="{{ $row->pmb_registration_stage_id }}"
                                    data-sequence="{{ $row->sequence }}"
                                    data-is_required="{{ $row->is_required }}"
                                    data-execution_type="{{ $row->execution_type }}"
                                    data-start_at="{{ $row->start_at }}"
                                    data-end_at="{{ $row->end_at }}"
                                    data-is_active="{{ $row->is_active }}"
                                >
                                    <x-icon icon="pencil"/>
                                </button>
                                <button
                                    type="button"
                                    class="button button--ghost button--danger button--icon-only button--sm"
                                    data-stisla-dialog-trigger="deleteDialog"
                                    data-delete-name="{{ $row->name }}"
                                    data-delete-action="{{ route('pmb.wave.path.stage.destroy', $row) }}"
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
                        <td colspan="100" class="px-6 py-12 text-center text-gray-400">
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
    title="Hapus"
    description="Data tahapan gelombang PMB yang dihapus tidak dapat dikembalikan."
    button-text="Hapus Tahapan Gelombang PMB"
/>
<x-form-dialog
    id="formDialog"
    title="Tahapan Gelombang"
    :fields="[
        [
            'name'=> 'pmb_wave_path_id',
            'label'=> '',
            'type'=> 'hidden',
        ],
        [
            'name' => 'pmb_registration_stage_id',
            'label' => 'Tahapan',
            'type' => 'select',
            'required' => true,
            'options'=> $registration_stages??[],
        ],
        [
            'name' => 'sequence',
            'label' => 'Urutan Tahapan',
            'type' => 'number',
            'placeholder' => 'Urutan Tahapan',
        ],
        [
            'name' => 'is_required',
            'label' => 'Wajibkan Tahapan',
            'type' => 'checkbox',
            'value' => 1,
        ],
        [
            'name' => 'execution_type',
            'label' => 'Jadwal Tahapan',
            'type' => 'select',
            'required' => true,
            'options'=> ['flexible'=>'Fleksibel','scheduled'=>'Terjadwal']
        ],
        [
            'name' => 'start_at',
            'label' => 'Jadwal Mulai (Jika Terjadwal)',
            'type' => 'datetime-local',
            'placeholder' => 'Tanggal Mulai',
        ],
        [
            'name' => 'end_at',
            'label' => 'Jadwal Selesai (Jika Terjadwal)',
            'type' => 'datetime-local',
            'placeholder' => 'Tanggal Selesai',
        ],
        [
            'name' => 'is_active',
            'label' => 'Tahapan Aktif',
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
