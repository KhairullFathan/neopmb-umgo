<div class="mb-5 space-y-3">
    {{-- Success --}}
    @if (session('success'))
        <div
            role="alert"
            class="flex items-start gap-3 rounded-xl border border-emerald-200
                   bg-emerald-50 p-4 text-emerald-800 shadow-sm"
        >
            <div class="flex size-9 shrink-0 items-center justify-center rounded-full bg-emerald-100">
                <x-icon icon="check" class="text-emerald-600" />
            </div>

            <div class="min-w-0 flex-1 pt-1">
                <p class="text-sm font-semibold">Berhasil</p>
                <p class="mt-1 text-sm leading-relaxed">
                    {{ session('success') }}
                </p>
            </div>

            <button
                type="button"
                onclick="this.closest('[role=alert]').remove()"
                class="shrink-0 rounded-lg p-1 text-emerald-600 transition
                       hover:bg-emerald-100 hover:text-emerald-800"
                aria-label="Tutup notifikasi"
            >
                <x-icon icon="times" />
            </button>
        </div>
    @endif

    {{-- Error Session --}}
    @if (session('error'))
        <div
            role="alert"
            class="flex items-start gap-3 rounded-xl border border-red-200
                   bg-red-50 p-4 text-red-800 shadow-sm"
        >
            <div class="flex size-9 shrink-0 items-center justify-center rounded-full bg-red-100">
                <x-icon icon="times" class="text-red-600" />
            </div>

            <div class="min-w-0 flex-1 pt-1">
                <p class="text-sm font-semibold">Terjadi Kesalahan</p>
                <p class="mt-1 text-sm leading-relaxed">
                    {{ session('error') }}
                </p>
            </div>

            <button
                type="button"
                onclick="this.closest('[role=alert]').remove()"
                class="shrink-0 rounded-lg p-1 text-red-600 transition
                       hover:bg-red-100 hover:text-red-800"
                aria-label="Tutup notifikasi"
            >
                <x-icon icon="times" />
            </button>
        </div>
    @endif

    {{-- Validation Message --}}
    @if ($errors->has('message'))
        <div
            role="alert"
            class="flex items-start gap-3 rounded-xl border border-red-200
                   bg-red-50 p-4 text-red-800 shadow-sm"
        >
            <div class="flex size-9 shrink-0 items-center justify-center rounded-full bg-red-100">
                <x-icon icon="times" class="text-red-600" />
            </div>

            <div class="min-w-0 flex-1 pt-1">
                <p class="text-sm font-semibold">Validasi Gagal</p>
                <p class="mt-1 text-sm leading-relaxed">
                    {{ $errors->first('message') }}
                </p>
            </div>

            <button
                type="button"
                onclick="this.closest('[role=alert]').remove()"
                class="shrink-0 rounded-lg p-1 text-red-600 transition
                       hover:bg-red-100 hover:text-red-800"
                aria-label="Tutup notifikasi"
            >
                <x-icon icon="times" />
            </button>
        </div>
    @endif
</div>

