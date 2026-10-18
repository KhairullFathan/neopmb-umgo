@extends('layouts.app-fe')

@section('title', $title)

@section('content')
@php
    $option_school_type = [];
    $school_name_label = '';
    $school_name_placeholder = '';
    $school_major_label = '';
    $school_major_placeholder = '';
    $school_type_label = '';
    if(in_array($pmb_wave_path->pmbRegistrationPath->code,['PASCA','PROFESI'])){
        $option_school_type = ['NEGERI','SWASTA'];
        $school_name_label = 'Asal Universitas';
        $school_name_placeholder = 'UNIVERSITAS MUHAMMADIYAH GORONTALO';
        $school_major_label = 'Program Studi';
        $school_major_placeholder = 'KEPERAWATAN';
        $school_type_label = 'Jenis Perguruan Tinggi';
    } else if(in_array($pmb_wave_path->pmbRegistrationPath->code, ['REGKES', 'REGKEDOK'])){
        $option_school_type = ['SMA','MA'];
        $school_name_label = 'Asal Sekolah';
        $school_name_placeholder = 'SMA N 1 GORONTALO';
        $school_major_label = 'Jurusan Sekolah';
        $school_major_placeholder = 'IPA';
        $school_type_label = 'Jenis Sekolah';
    }
    else{
        $option_school_type = ['SMA','MA','SMK'];
        $school_name_label = 'Asal Sekolah';
        $school_name_placeholder = 'SMA N 1 GORONTALO';
        $school_major_label = 'Jurusan Sekolah';
        $school_major_placeholder = 'IPA atau TKJ';
        $school_type_label = 'Jenis Sekolah';
    }
    $graduate_years = [];
    for ($year = date('Y'); $year >= date('Y') - 20; $year--) {
        $graduate_years[] = $year;
    }
@endphp
<section class="bg-brand-50 py-10 sm:py-14">
	<div class="mx-auto grid max-w-7xl gap-8 px-4 lg:grid-cols-7">

		{{-- Form --}}
		<div class="lg:col-span-4">
            <input type="hidden" name="">
			<div class="rounded-2xl bg-white p-6 shadow-md ring-1 ring-brand-100 sm:p-8">
				<h1 class="text-2xl font-bold text-slate-900">Pendaftaran Mahasiswa Baru</h1>
				<p class="mt-1 text-sm text-slate-500">Isi data awal Anda. Data lengkap akan diisi pada tahap berikutnya.</p>

				@if ($errors->any())
					<div role="alert" class="mt-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
						Periksa kembali isian Anda. Ada {{ $errors->count() }} data yang perlu diperbaiki.
                        {{-- {{ dd($errors) }} --}}
					</div>
				@endif

				<form method="POST" action="{{ route('home.registration.store') }}" class="mt-6 space-y-5" enctype="multipart/form-data">
					@csrf
					@method('POST')
                    <input type="hidden" name="pmb_wave_path_id" value="{{ $pmb_wave_path->id }}">
					<div class="mb-2">
						<label for="name" class="block text-sm font-medium text-slate-800">Nama lengkap</label>
						<input id="name" name="name" type="text" value="{{ old('name') }}" required  placeholder="Sesuai kartu identitas" class="mt-1 block w-full rounded-lg border px-3 py-2.5 text-sm border-slate-300 focus:border-brand-600 focus:ring-2 focus:ring-brand-200">
						@error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
					</div>
					<div class="mb-2">
						<label for="identity_number" class="block text-sm font-medium text-slate-800">NIK/No Passport bagi mahasiswa internasional</label>
						<input id="identity_number" name="identity_number" type="text" value="{{ old('identity_number') }}" required inputmode="numeric"  placeholder="Sesuai kartu identitas" class="mt-1 block w-full rounded-lg border px-3 py-2.5 text-sm border-slate-300 focus:border-brand-600 focus:ring-2 focus:ring-brand-200">
						@error('identity_number')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
					</div>
					<div class="mb-2">
						<label for="phone" class="block text-sm font-medium text-slate-800">Nomor telepon (WhatsApp aktif)</label>
						<input id="phone" name="phone" type="tel" value="{{ old('phone') }}" required inputmode="tel"  placeholder="081234567890" class="mt-1 block w-full rounded-lg border px-3 py-2.5 text-sm border-slate-300 focus:border-brand-600 focus:ring-2 focus:ring-brand-200">
						@error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
					</div>
					<div class="mb-2">
						<label for="school_origin" class="block text-sm font-medium text-slate-800">{{ $school_name_label }}</label>
						<input id="school_origin" name="school_origin" type="text" value="{{ old('school_origin') }}" required  placeholder="{{ $school_name_placeholder }}" class="mt-1 block w-full rounded-lg border px-3 py-2.5 text-sm border-slate-300 focus:border-brand-600 focus:ring-2 focus:ring-brand-200">
						@error('school_origin')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
					</div>
                    <div class="mb-2">
                        <label for="school_type" class="block text-sm font-medium text-slate-800">{{ $school_type_label }}</label>
                        <select id="school_type" name="school_type" required class="mt-1 block w-full rounded-lg border bg-white px-3 py-2.5 text-sm border-slate-300 focus:border-brand-600 focus:ring-2 focus:ring-brand-200">
                        <option value="">Pilih Opsi</option>
                        @foreach ($option_school_type as $item)
                            <option value="{{ $item }}" @selected($item == old('school_type'))>{{ $item }}</option>
                        @endforeach
                        </select>
                        @error('school_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
					<div class="mb-2">
						<label for="school_major" class="block text-sm font-medium text-slate-800">{{ $school_major_label }}</label>
						<input id="school_major" name="school_major" type="text" value="{{ old('school_major') }}" required  placeholder="{{ $school_major_placeholder }}" class="mt-1 block w-full rounded-lg border px-3 py-2.5 text-sm border-slate-300 focus:border-brand-600 focus:ring-2 focus:ring-brand-200">
						@error('school_major')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
					</div>
                    <div class="mb-2">
                        <label for="graduate_year" class="block text-sm font-medium text-slate-800">Tahun Lulus</label>
                        <select id="graduate_year" name="graduate_year" required class="mt-1 block w-full rounded-lg border bg-white px-3 py-2.5 text-sm border-slate-300 focus:border-brand-600 focus:ring-2 focus:ring-brand-200">
                        <option value="">Pilih Opsi</option>
                        @foreach ($graduate_years as $item)
                            <option value="{{ $item }}" @selected($item == old('graduate_year'))>{{ $item }}</option>
                        @endforeach
                        </select>
                        @error('study_programs.0')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-2">
                        <label for="study_programs[]" class="block text-sm font-medium text-slate-800">Pilihan Program Studi 1</label>
                        <select id="study_programs[]" name="study_programs[]" required class="mt-1 block w-full rounded-lg border bg-white px-3 py-2.5 text-sm border-slate-300 focus:border-brand-600 focus:ring-2 focus:ring-brand-200">
                        <option value="">Pilih Opsi</option>
                        @foreach ($pmb_wave_path->studyPrograms as $item)
                            <option value="{{ $item->code }}" @selected($item->code === old('study_programs.0'))>{{ $item->degree_level }} {{ $item->name }}</option>
                        @endforeach
                        </select>
                        @error('study_programs.0')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-2">
                        <label for="study_programs[]" class="block text-sm font-medium text-slate-800">Pilihan Program Studi 2</label>
                        <select id="study_programs[]" name="study_programs[]" {{ in_array($pmb_wave_path->pmbRegistrationPath->code,['REG'])?'required':'' }} class="mt-1 block w-full rounded-lg border bg-white px-3 py-2.5 text-sm border-slate-300 focus:border-brand-600 focus:ring-2 focus:ring-brand-200">
                        <option value="">Pilih Opsi</option>
                        @foreach ($pmb_wave_path->studyPrograms as $item)
                            <option value="{{ $item->code }}" @selected($item->code === old('study_programs.1'))>{{ $item->degree_level }} {{ $item->name }}</option>
                        @endforeach
                        </select>
                        @error('study_programs.1')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

					<div class="mb-2">
						<label class="flex items-start gap-3 text-sm text-slate-700">
							<input type="checkbox" name="setuju" value="1" @checked(old('setuju')) class="mt-0.5 h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-200">
							<span>Saya menyatakan data yang saya isi sudah benar dan setuju datanya digunakan untuk keperluan penerimaan mahasiswa baru.</span>
						</label>
						@error('setuju')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
					</div>

					<button type="submit" class="w-full rounded-lg bg-brand-600 px-4 py-3 text-sm font-semibold text-white hover:bg-brand-700">Buat Pendaftaran</button>
					<p class="text-center text-sm text-slate-500">Sudah punya akun? <a href="{{ url('/login') }}" class="font-semibold text-brand-600 hover:underline">Masuk</a></p>
				</form>
			</div>
		</div>

		{{-- Ringkasan --}}
		@if (@$pmb_wave_path)
		<aside class="lg:col-span-3">
			<div class="rounded-2xl bg-brand-600 p-6 text-white">
				<p class="text-sm font-semibold text-brand-100">Anda memilih</p>
				<p class="mt-1 text-xl font-bold">Periode: {{ $pmb_wave_path->pmbWave->pmbPeriod->name }}</p>
				<p class="mt-1 text-xl font-bold">Gelombang: {{ $pmb_wave_path->pmbWave->name }}</p>
				<p class="mt-1 text-xl font-bold">Jalur: {{ $pmb_wave_path->pmbRegistrationPath->name }}</p>
			</div>

			<div class="mt-5 rounded-2xl border border-brand-100 bg-white p-6 text-sm text-slate-600">
				<p class="font-semibold text-slate-900">Tahapan pendaftaran pada jalur ini</p>
				<ol class="mt-3 list-decimal space-y-1.5 pl-5">
					@foreach ($pmb_wave_path->stages as $item)
						<li>{{ $item->pmbRegistrationStage->name }}</li>
					@endforeach
                    <li>Menjadi Mahasiswa</li>
				</ol>
				<p class="mt-4">Butuh bantuan? <a href="https://wa.me/{{ config('pmb.wa_bot') }}" target="_blank" rel="noopener" class="font-semibold text-brand-600 hover:underline">Tanya WA Bot</a></p>
			</div>
		</aside>
		@endif
	</div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('input[type="text"]').forEach(function (input) {
            input.addEventListener('input', function () {
                const start = this.selectionStart;
                const end = this.selectionEnd;

                this.value = this.value.toLocaleUpperCase('id-ID');

                // Pertahankan posisi kursor
                this.setSelectionRange(start, end);
            });
        });
    });
</script>
@endpush
