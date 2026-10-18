@extends('layouts.app-fe')

@section('title', @$title)

@section('content')
@php
    $graduate_years = [];
    for ($year = date('Y'); $year >= date('Y') - 20; $year--) {
        $graduate_years[] = $year;
    }
@endphp

{{-- Header --}}
<section class="bg-gradient-to-br from-blue-700 via-brand-600 to-cyan-400 text-white">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:py-12">
        <nav aria-label="Breadcrumb" class="text-sm text-brand-100">
            <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a>
            <span class="mx-1">/</span>
            <span aria-current="page">Data Saya</span>
        </nav>
        <h1 class="mt-3 text-3xl font-bold sm:text-4xl">Data Saya</h1>
        <p class="mt-2 max-w-2xl text-brand-100">Lengkapi dan perbarui biodata Anda. Pastikan data sesuai dengan dokumen identitas.</p>
    </div>
</section>

<section class="bg-white py-10">
    <div class="mx-auto max-w-7xl px-4">

        @include('components.fe.alert')

        <form class="order-form" method="post" enctype="multipart/form-data" action="{{ route('my.applicant.update', $data) }}">
            @method('PUT')
            @csrf

            <div class="grid gap-8 lg:grid-cols-3">

                {{-- ===================== KONTEN UTAMA ===================== --}}
                <div class="space-y-8 lg:col-span-2">

                    {{-- Data Pribadi --}}
                    <div class="fe-card">
                        <div class="fe-card__header">
                            <span class="fe-card__number">1</span>
                            <h2 class="fe-card__title">Data Pribadi</h2>
                        </div>
                        <div class="fe-card__body">
                            <div class="grid gap-5 sm:grid-cols-2">
                                <div>
                                    <label for="name" class="fe-label">Nama</label>
                                    <input type="text" class="fe-input" id="name" name="name"
                                        value="{{ old('name' ,$data->name) }}" />
                                    @error('name')
                                        <p class="fe-error">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="identity_number" class="fe-label">Nomor Identitas (KTP/Passport)</label>
                                    <input type="text" class="fe-input" id="identity_number" name="identity_number" readonly
                                        value="{{ old('identity_number' ,$data->identity_number) }}" />
                                    @error('identity_number')
                                        <p class="fe-error">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="gender" class="fe-label">Jenis Kelamin</label>
                                    <select name="gender" id="gender" class="fe-input">
                                        <option value="">Pilih Opsi</option>
                                        <option value="MALE" @selected(old('gender', $data->gender)=='MALE')>LAKI-LAKI</option>
                                        <option value="FEMALE" @selected(old('gender', $data->gender)=='FEMALE')>PEREMPUAN</option>
                                    </select>
                                    @error('gender')
                                        <p class="fe-error">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="religion" class="fe-label">Agama</label>
                                    <select name="religion" id="religion" class="fe-input">
                                        <option value="">Pilih Opsi</option>
                                        @foreach (\App\Models\Applicant::religions() as $item)
                                        <option value="{{ $item }}" @selected(old('religion', $data->religion)==$item)>{{$item}}</option>
                                        @endforeach
                                    </select>
                                    @error('religion')
                                        <p class="fe-error">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="birth_place" class="fe-label">Tempat Lahir</label>
                                    <input type="text" class="fe-input" id="birth_place" name="birth_place"
                                        value="{{ old('birth_place' ,$data->birth_place) }}" />
                                    @error('birth_place')
                                        <p class="fe-error">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="birth_date" class="fe-label">Tanggal Lahir</label>
                                    <input type="date" class="fe-input" id="birth_date" name="birth_date"
                                        value="{{ old('birth_date' ,\Carbon\Carbon::parse($data->birth_date)->format('Y-m-d')) }}" />
                                    @error('birth_date')
                                        <p class="fe-error">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="email" class="fe-label">Email</label>
                                    <input type="text" class="fe-input" id="email" name="email"
                                        value="{{ old('email', $data->user->email) }}" />
                                    @error('email')
                                        <p class="fe-error">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="phone" class="fe-label">No Telephone (Whatsapp)</label>
                                    <input type="text" class="fe-input" id="phone" name="phone"
                                        value="{{ old('phone', $data->phone) }}" />
                                    @error('phone')
                                        <p class="fe-error">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="nationality" class="fe-label">Kewarganegaraan</label>
                                    <select name="nationality" id="nationality" class="fe-input">
                                        <option value="">Pilih Opsi</option>
                                        @foreach (['WNI','WNA'] as $item)
                                        <option value="{{ $item }}" @selected(old('nationality', $data->nationality)==$item)>{{$item}}</option>
                                        @endforeach
                                    </select>
                                    @error('nationality')
                                        <p class="fe-error">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="country_of_origin" class="fe-label">Asal Negara</label>
                                    <input type="text" class="fe-input" id="country_of_origin" name="country_of_origin"
                                        placeholder="INDONESIA"
                                        value="{{ old('INDONESIA', $data->country_of_origin) }}" />
                                    @error('country_of_origin')
                                        <p class="fe-error">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="mother_name" class="fe-label">Nama Gadis Ibu Kandung</label>
                                    <input type="text" class="fe-input" id="mother_name" name="mother_name"
                                        value="{{ old('mother_name' ,$data->mother_name) }}" />
                                    @error('mother_name')
                                        <p class="fe-error">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="parent_phone" class="fe-label">No Telephone Orang TUa</label>
                                    <input type="text" class="fe-input" id="parent_phone" name="parent_phone"
                                        value="{{ old('parent_phone', $data->parent_phone) }}" />
                                    @error('parent_phone')
                                        <p class="fe-error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Data Domisili --}}
                    <div class="fe-card">
                        <div class="fe-card__header">
                            <span class="fe-card__number">2</span>
                            <h2 class="fe-card__title">Data Domisili</h2>
                        </div>
                        <div class="fe-card__body">
                            <div class="grid gap-5 sm:grid-cols-2">
                                <div>
                                    <label for="code_province" class="fe-label">Provinsi</label>
                                    <select name="code_province" id="code_province" class="fe-input">
                                        <option value="">Memuat...</option>
                                    </select>
                                    @error('code_province')
                                        <p class="fe-error">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="code_regency" class="fe-label">Kabupaten / Kota</label>
                                    <select name="code_regency" id="code_regency" class="fe-input">
                                        <option value="">Memuat...</option>
                                    </select>
                                    @error('code_regency')
                                        <p class="fe-error">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="code_district" class="fe-label">Kecamatan</label>
                                    <select name="code_district" id="code_district" class="fe-input">
                                        <option value="">Memuat...</option>
                                    </select>
                                    @error('code_district')
                                        <p class="fe-error">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="code_village" class="fe-label">Desa / Kelurahan</label>
                                    <select name="code_village" id="code_village" class="fe-input" data-code_village="{{ old('code_village', $data->code_village) }}">
                                        <option value="">Memuat...</option>
                                    </select>
                                    @error('code_village')
                                        <p class="fe-error">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="sm:col-span-2">
                                    <label for="address" class="fe-label">Isikan Alamat jika tidak ada pada pilihan di atas (Luar Negeri)</label>
                                    <textarea name="address" id="address" rows="3" class="fe-input">{{ old('address' ,$data->address) }}</textarea>
                                    @error('address')
                                        <p class="fe-error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Data Pendidikan Terakhir --}}
                    <div class="fe-card">
                        <div class="fe-card__header">
                            <span class="fe-card__number">3</span>
                            <h2 class="fe-card__title">Data Pendidikan Terakhir</h2>
                        </div>
                        <div class="fe-card__body">
                            <div class="grid gap-5 sm:grid-cols-2">
                                <div>
                                    <label for="school_origin" class="fe-label">Asal</label>
                                    <input type="text" class="fe-input" id="school_origin" name="school_origin"
                                        value="{{ old('school_origin' ,$data->school_origin) }}" />
                                    @error('school_origin')
                                        <p class="fe-error">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="school_type" class="fe-label">Jenis</label>
                                    <input type="text" class="fe-input" id="school_type" name="school_type"
                                        placeholder="SMA, MA, SMK"
                                        value="{{ old('school_type' ,$data->school_type) }}" />
                                    @error('school_type')
                                        <p class="fe-error">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="school_major" class="fe-label">Jurusan/Program Studi</label>
                                    <input type="text" class="fe-input" id="school_major" name="school_major"
                                        placeholder="Jurusan / Program Studi"
                                        value="{{ old('school_major' ,$data->school_major) }}" />
                                    @error('school_major')
                                        <p class="fe-error">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="school_major" class="fe-label">Tahun Lulus</label>
                                    <select id="graduate_year" name="graduate_year" required class="fe-input">
                                        <option value="">Pilih Opsi</option>
                                    @foreach ($graduate_years as $item)
                                        <option value="{{ $item }}" @selected($item == old('graduate_year', $data->graduate_year))>{{ $item }}</option>
                                    @endforeach
                                    </select>
                                    @error('graduate_year')
                                        <p class="fe-error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===================== SIDEBAR ===================== --}}
                <aside class="lg:sticky lg:top-24 lg:self-start">
                    <div class="overflow-hidden rounded-xl rounded-tl-[2.5rem] bg-white shadow-md ring-1 ring-brand-100">
                        <div class="rounded-br-[2.5rem] bg-brand-600 px-6 pb-8 pt-7 text-white">
                            <div class="flex items-center gap-3">
                                <span class="grid h-12 w-12 shrink-0 place-items-center rounded-full bg-white/20 text-lg font-bold">
                                    {{ strtoupper(mb_substr($data->name ?? '?', 0, 1)) }}
                                </span>
                                <div class="min-w-0">
                                    <p class="truncate font-semibold">{{ $data->name }}</p>
                                    <p class="truncate text-sm text-brand-100">{{ $data->user->email }}</p>
                                </div>
                            </div>
                            <span class="mt-4 inline-block rounded-full bg-white/20 px-3 py-0.5 text-xs font-semibold">Draft</span>
                        </div>
                        <div class="space-y-2 p-5">
                            <button type="submit" class="block w-full rounded-md bg-brand-600 px-4 py-3 text-center text-sm font-semibold text-white hover:bg-brand-700">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>

                    <div class="mt-6 rounded-xl border border-brand-100 p-5">
                        <p class="text-sm font-semibold text-slate-900">Tips pengisian</p>
                        <ul class="mt-3 list-disc space-y-2 pl-5 text-sm text-slate-600">
                            <li>Isi nama sesuai KTP / paspor.</li>
                            <li>Gunakan nomor WhatsApp yang aktif.</li>
                            <li>Pilih domisili secara berurutan dari provinsi hingga desa.</li>
                        </ul>
                    </div>
                </aside>
            </div>
        </form>
    </div>
</section>
@endsection

@push('scripts')
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
