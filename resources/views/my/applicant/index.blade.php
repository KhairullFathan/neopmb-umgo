@extends('layouts.app')

@section('title', @$title)

@section('header')
<div class="page__headline">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb__item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb__item" aria-current="page">Data Saya</li>
        </ol>
    </nav>
    <h1 class="page__title">Data Saya</h1>
    <p class="page__description">
        Biodata Data saya
    </p>
</div>
<div class="page__action">
</div>
@endsection

@section('content')
<section class="page__section">
    @include('components.alert')
    <form class="order-form" method="post" enctype="multipart/form-data" action="{{ route('my.applicant.update', $data) }}">
        @method('PUT')
        @csrf
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-12">
                <div class="flex flex-col gap-3">
                    <div class="card">
                        <div class="card__header">
                            <span class="card__title">Data Pribadi</span>
                        </div>
                        <div class="card__body">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="field">
                                    <label for="name" class="field__label">Nama</label>
                                    <input
                                        type="text"
                                        class="input"
                                        id="name"
                                        name="name"
                                        readonly
                                        value="{{ old('name' ,$data->name) }}"
                                    />
                                    @error('name')
                                        <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="field">
                                    <label for="identity_number" class="field__label">Nomor Identitas (KTP/Passport)</label>
                                    <input
                                        type="text"
                                        class="input"
                                        id="identity_number"
                                        name="identity_number"
                                        readonly
                                        value="{{ old('identity_number' ,$data->identity_number) }}"
                                    />
                                    @error('identity_number')
                                        <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="field">
                                    <label for="gender" class="field__label">Jenis Kelamin</label>
                                    <select name="gender" id="gender" class="select">
                                        <option value="">Pilih Opsi</option>
                                        <option value="MALE" @selected(old('gender', $data->gender)=='MALE')>LAKI-LAKI</option>
                                        <option value="FEMALE" @selected(old('gender', $data->gender)=='FEMALE')>PEREMPUAN</option>
                                    </select>
                                    @error('gender')
                                        <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="field">
                                    <label for="religion" class="field__label">Agama</label>
                                    <select name="religion" id="religion" class="select">
                                        <option value="">Pilih Opsi</option>
                                        @foreach (\App\Models\Applicant::religions() as $item)
                                        <option value="{{ $item }}" @selected(old('religion', $data->religion)==$item)>{{$item}}</option>
                                        @endforeach
                                    </select>
                                    @error('religion')
                                        <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="field">
                                    <label for="birth_place" class="field__label">Tempat Lahir</label>
                                    <input
                                        type="text"
                                        class="input"
                                        id="birth_place"
                                        name="birth_place"

                                        value="{{ old('birth_place' ,$data->birth_place) }}"
                                    />
                                    @error('birth_place')
                                        <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="field">
                                    <label for="birth_date" class="field__label">Tanggal Lahir</label>
                                    <input
                                        type="date"
                                        class="input"
                                        id="birth_date"
                                        name="birth_date"
                                        placeholder="Gorontalo"
                                        value="{{ old('birth_date' ,\Carbon\Carbon::parse($data->birth_date)->format('Y-m-d')) }}"
                                    />
                                    @error('birth_date')
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

                                        value="{{ old('email', $data->user->email) }}"
                                    />
                                    @error('email')
                                        <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="field">
                                    <label for="phone" class="field__label">No Telephone (Whatsapp)</label>
                                    <input
                                        type="text"
                                        class="input"
                                        id="phone"
                                        name="phone"

                                        value="{{ old('phone', $data->phone) }}"
                                    />
                                    @error('phone')
                                        <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="field">
                                    <label for="nationality" class="field__label">Kewarganegaraan</label>
                                    <select name="nationality" id="nationality" class="select">
                                        <option value="">Pilih Opsi</option>
                                        @foreach (['WNI','WNA'] as $item)
                                        <option value="{{ $item }}" @selected(old('nationality', $data->nationality)==$item)>{{$item}}</option>
                                        @endforeach
                                    </select>
                                    @error('nationality')
                                        <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="field">
                                    <label for="country_of_origin" class="field__label">Asal Negara</label>
                                    <input
                                        type="text"
                                        class="input"
                                        id="country_of_origin"
                                        name="country_of_origin"
                                        placeholder="INDONESIA"
                                        value="{{ old('INDONESIA', $data->country_of_origin) }}"
                                    />
                                    @error('country_of_origin')
                                        <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="field">
                                    <label for="mother_name" class="field__label">Nama Gadis Ibu Kandung</label>
                                    <input
                                        type="text"
                                        class="input"
                                        id="mother_name"
                                        name="mother_name"

                                        value="{{ old('mother_name' ,$data->mother_name) }}"
                                    />
                                    @error('mother_name')
                                        <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="field">
                                    <label for="parent_phone" class="field__label">No Telephone Orang TUa</label>
                                    <input
                                        type="text"
                                        class="input"
                                        id="parent_phone"
                                        name="parent_phone"

                                        value="{{ old('parent_phone', $data->parent_phone) }}"
                                    />
                                    @error('parent_phone')
                                        <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 lg:col-span-6">
                <div class="flex flex-col gap-3">
                    <div class="card">
                        <div class="card__header">
                            <span class="card__title">Data Domisili</span>
                        </div>
                        <div class="card__body">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="field">
                                    <label for="code_province" class="field__label">Provinsi</label>
                                    <select name="code_province" id="code_province" class="select">
                                        <option value="">Memuat...</option>
                                    </select>
                                    @error('code_province')
                                        <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="field">
                                    <label for="code_regency" class="field__label">Kabupaten / Kota</label>
                                    <select name="code_regency" id="code_regency" class="select">
                                        <option value="">Memuat...</option>
                                    </select>
                                    @error('code_regency')
                                        <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="field">
                                    <label for="code_district" class="field__label">Kecamatan</label>
                                    <select name="code_district" id="code_district" class="select">
                                        <option value="">Memuat...</option>
                                    </select>
                                    @error('code_district')
                                        <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="field">
                                    <label for="code_village" class="field__label">Desa / Kelurahan</label>
                                    <select name="code_village" id="code_village" class="select" data-code_village="{{ old('code_village', $data->code_village) }}">
                                        <option value="">Memuat...</option>
                                    </select>
                                    @error('code_village')
                                        <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="field col-span-2">
                                    <label for="address" class="field__label">Isikan Alamat jika tidak ada pada pilihan di atas (Luar Negeri)</label>
                                    <textarea name="address" id="address" class="textarea">{{ old('address' ,$data->address) }}</textarea>
                                    @error('address')
                                        <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 lg:col-span-6">
                <div class="flex flex-col gap-3">
                    <div class="card">
                        <div class="card__header">
                            <span class="card__title">Data Pendidikan Terakhir</span>
                        </div>
                        <div class="card__body">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="field">
                                    <label for="school_origin" class="field__label">Asal</label>
                                    <input
                                        type="text"
                                        class="input"
                                        id="school_origin"
                                        name="school_origin"

                                        value="{{ old('school_origin' ,$data->school_origin) }}"
                                    />
                                    @error('school_origin')
                                        <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="field">
                                    <label for="school_type" class="field__label">Jenis</label>
                                    <input
                                        type="text"
                                        class="input"
                                        id="school_type"
                                        name="school_type"
                                        placeholder="Jurusan / Program Studi"
                                        value="{{ old('school_type' ,$data->school_type) }}"
                                    />
                                    @error('school_type')
                                        <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="field">
                                    <label for="school_major" class="field__label">Jurusan/Program Studi</label>
                                    <input
                                        type="text"
                                        class="input"
                                        id="school_major"
                                        name="school_major"
                                        placeholder="Jurusan / Program Studi"
                                        value="{{ old('school_major' ,$data->school_major) }}"
                                    />
                                    @error('school_major')
                                        <p class="text-xs text-danger mt-1.5 ml-1">{{ $message }}</p>
                                    @enderror
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

<script>

document.addEventListener('DOMContentLoaded', function () {
    const provinceSelect = document.getElementById('code_province');
    const regencySelect = document.getElementById('code_regency');
    const districtSelect = document.getElementById('code_district');
    const villageSelect = document.getElementById('code_village');

    const initialVillageCode = villageSelect.dataset.code_village || villageSelect.value;

    const api = {
        provinces: @json(route('api.domicile.provinces')),
        regencies: @json(route('api.domicile.regencies')),
        districts: @json(route('api.domicile.districts')),
        villages: @json(route('api.domicile.villages')),
        villageDetail: @json(route('api.domicile.village-detail')),
    };

    function resetSelect(select, placeholder) {
        select.replaceChildren();

        const option = document.createElement('option');
        option.value = '';
        option.textContent = placeholder;

        select.appendChild(option);
        select.disabled = true;
    }

    function populateSelect(select, items, placeholder) {
        resetSelect(select, placeholder);

        items.forEach(function (item) {
            const option = document.createElement('option');

            option.value = item.code;
            option.textContent = item.name;

            select.appendChild(option);
        });

        select.disabled = false;
    }

    async function loadData(url, select, placeholder) {
        resetSelect(select, 'Memuat data...');

        try {
            const response = await fetch(url, {
                headers: {
                    Accept: 'application/json',
                },
            });

            if (!response.ok) {
                throw new Error('Gagal mengambil data wilayah.');
            }

            const items = await response.json();

            populateSelect(select, items, placeholder);

            return true;
        } catch (error) {
            resetSelect(
                select,
                'Gagal memuat data. Silakan coba lagi.'
            );

            console.error(error);

            return false;
        }
    }

    function makeUrl(baseUrl, parameter, value) {
        const url = new URL(baseUrl, window.location.origin);
        url.searchParams.set(parameter, value);

        return url.toString();
    }

    // Memuat hierarki berdasarkan kode desa yang sudah tersimpan.
    async function restoreDomicile(villageCode) {
        const url = makeUrl(
            api.villageDetail,
            'code_village',
            villageCode
        );

        const response = await fetch(url, {
            headers: {
                Accept: 'application/json',
            },
        });

        if (!response.ok) {
            throw new Error('Gagal mengambil detail domisili.');
        }

        const detail = await response.json();

        // 1. Provinsi
        if (!await loadData(
            api.provinces,
            provinceSelect,
            'Pilih Provinsi'
        )) return;

        provinceSelect.value = String(detail.code_province);

        // 2. Kabupaten/Kota
        if (!await loadData(
            makeUrl(
                api.regencies,
                'code_province',
                detail.code_province
            ),
            regencySelect,
            'Pilih Kabupaten/Kota'
        )) return;

        regencySelect.value = String(detail.code_regency);

        // 3. Kecamatan
        if (!await loadData(
            makeUrl(
                api.districts,
                'code_regency',
                detail.code_regency
            ),
            districtSelect,
            'Pilih Kecamatan'
        )) return;

        districtSelect.value = String(detail.code_district);

        // 4. Desa/Kelurahan
        if (!await loadData(
            makeUrl(
                api.villages,
                'code_district',
                detail.code_district
            ),
            villageSelect,
            'Pilih Desa/Kelurahan'
        )) return;

        villageSelect.value = String(detail.code_village);
    }

    // Jika sudah ada kode desa, pulihkan semua pilihan.
    // Jika belum, cukup muat daftar provinsi.
    if (initialVillageCode) {
        restoreDomicile(initialVillageCode).catch(function (error) {
            console.error(error);
        });
    } else {
        loadData(
            api.provinces,
            provinceSelect,
            'Pilih Provinsi'
        );
    }

    // Ketika provinsi berubah.
    provinceSelect.addEventListener('change', async function () {
        resetSelect(regencySelect, 'Pilih Kabupaten/Kota');
        resetSelect(districtSelect, 'Pilih Kecamatan');
        resetSelect(villageSelect, 'Pilih Desa/Kelurahan');

        if (!this.value) return;

        await loadData(
            makeUrl(api.regencies, 'code_province', this.value),
            regencySelect,
            'Pilih Kabupaten/Kota'
        );
    });

    // Ketika kabupaten/kota berubah.
    regencySelect.addEventListener('change', async function () {
        resetSelect(districtSelect, 'Pilih Kecamatan');
        resetSelect(villageSelect, 'Pilih Desa/Kelurahan');

        if (!this.value) return;

        await loadData(
            makeUrl(api.districts, 'code_regency', this.value),
            districtSelect,
            'Pilih Kecamatan'
        );
    });

    // Ketika kecamatan berubah.
    districtSelect.addEventListener('change', async function () {
        resetSelect(villageSelect, 'Pilih Desa/Kelurahan');

        if (!this.value) return;

        await loadData(
            makeUrl(api.villages, 'code_district', this.value),
            villageSelect,
            'Pilih Desa/Kelurahan'
        );
    });
});
</script>

@endpush
