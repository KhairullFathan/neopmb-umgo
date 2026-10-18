
@props([
    'id',
    'title' => 'Form',
    'fields' => [],
    'action' => '',
    'method' => 'POST',
    'submitText' => 'Simpan',
])
<div
    class="dialog"
    id="{{ $id }}"
    data-stisla-dialog
    data-state="closed"
    role="dialog"
    aria-modal="true"
    aria-labelledby="{{ $id }}Label"
    aria-hidden="true"
    tabindex="-1"
>
    <div
        class="dialog__backdrop"
        data-stisla-dialog-dismiss
    ></div>

    <div class="dialog__panel">
        <div class="dialog__content">

            {{-- Close --}}
            <button
                type="button"
                class="dialog__close"
                data-stisla-dialog-dismiss
                aria-label="Close"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="1em"
                    height="1em"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                >
                    <path d="M6 6l12 12M18 6L6 18" />
                </svg>
            </button>

            {{-- Header --}}
            <div class="dialog__header">
                <h3
                    class="dialog__title"
                    id="{{ $id }}Label"
                >
                    {{ $title }}
                </h3>
            </div>

            {{-- Form --}}
            <form
                id="{{ $id }}Form"
                action="{{ $action }}"
                method="POST"
            >
                @csrf

                {{-- PUT / PATCH --}}
                <input
                    type="hidden"
                    name="_method"
                    id="{{ $id }}Method"
                    value="{{ strtoupper($method) }}"
                >

                <div class="dialog__body flex flex-col gap-4">

                    @foreach($fields as $field)

                        @php
                            $name = $field['name'];
                            $type = $field['type'] ?? 'text';
                            $label = $field['label'] ?? ucfirst($name);

                            $fieldId = $id . '_' .
                                str_replace(
                                    ['.', '[', ']'],
                                    '_',
                                    $name
                                );

                            $placeholder = $field['placeholder'] ?? '';
                            $required = $field['required'] ?? false;
                            $options = $field['options'] ?? [];
                            $value = $field['value'] ?? 1;
                        @endphp

                        {{-- ===================================================== --}}
                        {{-- CHECKBOX --}}
                        {{-- ===================================================== --}}

                        @if($type === 'checkbox')

                            <div class="field">

                                <div class="field__item">

                                    {{-- <input
                                        type="hidden"
                                        name="{{ $name }}"
                                        value="0"
                                    > --}}

                                    <input
                                        name="{{ $name }}"
                                        value="{{ $value }}"
                                        class="checkbox"
                                        type="checkbox"
                                        id="{{ $fieldId }}"
                                        @required($required)
                                    />

                                    <label
                                        class="field__label"
                                        for="{{ $fieldId }}"
                                    >
                                        {{ $label }}

                                        @if($required)
                                            <span class="text-danger">*</span>
                                        @endif
                                    </label>

                                </div>

                                @error($name)
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>

                        {{-- ===================================================== --}}
                        {{-- RADIO --}}
                        {{-- ===================================================== --}}

                        @elseif($type === 'radio')

                            <div class="field">

                                <label class="field__label">
                                    {{ $label }}

                                    @if($required)
                                        <span class="text-danger">*</span>
                                    @endif
                                </label>

                                <div class="flex flex-col gap-2">

                                    @foreach($options as $optionValue => $optionLabel)

                                        @php
                                            $radioId = $fieldId . '_' . $optionValue;
                                        @endphp

                                        <div class="field__item">

                                            <input
                                                type="radio"
                                                name="{{ $name }}"
                                                value="{{ $optionValue }}"
                                                id="{{ $radioId }}"
                                                class="radio"
                                                @required($required)
                                            />

                                            <label
                                                class="field__label"
                                                for="{{ $radioId }}"
                                            >
                                                {{ $optionLabel }}
                                            </label>

                                        </div>

                                    @endforeach

                                </div>

                                @error($name)
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>

                        {{-- ===================================================== --}}
                        {{-- SELECT --}}
                        {{-- ===================================================== --}}

                        @elseif($type === 'select')

                            <div class="field">

                                <label
                                    for="{{ $fieldId }}"
                                    class="field__label"
                                >
                                    {{ $label }}

                                    @if($required)
                                        <span class="text-danger">*</span>
                                    @endif
                                </label>

                                <select
                                    name="{{ $name }}"
                                    id="{{ $fieldId }}"
                                    class="select"
                                    @required($required)
                                >

                                    <option value="">
                                        {{ $placeholder ?: 'Pilih ' . $label }}
                                    </option>

                                    @foreach($options as $optionValue => $optionLabel)

                                        <option value="{{ $optionValue }}">
                                            {{ $optionLabel }}
                                        </option>

                                    @endforeach

                                </select>

                                @error($name)
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>

                        {{-- ===================================================== --}}
                        {{-- TEXTAREA --}}
                        {{-- ===================================================== --}}

                        @elseif($type === 'textarea')

                            <div class="field">

                                <label
                                    for="{{ $fieldId }}"
                                    class="field__label"
                                >
                                    {{ $label }}

                                    @if($required)
                                        <span class="text-danger">*</span>
                                    @endif
                                </label>

                                <textarea
                                    name="{{ $name }}"
                                    id="{{ $fieldId }}"
                                    class="textarea"
                                    placeholder="{{ $placeholder }}"
                                    @required($required)
                                ></textarea>

                                @error($name)
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>
                        {{-- ===================================================== --}}
                        {{-- CURRENCY INPUT --}}
                        {{-- ===================================================== --}}
                        @elseif($type === 'currency')
                        <div class="field">

                                <label
                                    for="{{ $fieldId }}"
                                    class="field__label"
                                >
                                    {{ $label }}

                                    @if($required)
                                        <span class="text-danger">*</span>
                                    @endif
                                </label>

                                <input
                                    type="input"
                                    name="{{ $name }}"
                                    id="{{ $fieldId }}"
                                    class="input currency"
                                    placeholder="{{ $placeholder }}"
                                    @required($required)
                                >

                                @error($name)
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>
                        {{-- ===================================================== --}}
                        {{-- HIDDEN INPUT --}}
                        {{-- ===================================================== --}}
                        @elseif($type === 'hidden')
                            <input type="hidden" name="{{ $name }}">

                        {{-- ===================================================== --}}
                        {{-- DEFAULT INPUT --}}
                        {{-- ===================================================== --}}

                        @else

                            <div class="field">

                                <label
                                    for="{{ $fieldId }}"
                                    class="field__label"
                                >
                                    {{ $label }}

                                    @if($required)
                                        <span class="text-danger">*</span>
                                    @endif
                                </label>

                                <input
                                    type="{{ $type }}"
                                    name="{{ $name }}"
                                    id="{{ $fieldId }}"
                                    class="input"
                                    placeholder="{{ $placeholder }}"
                                    @required($required)
                                >

                                @error($name)
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>

                        @endif

                    @endforeach

                </div>

                {{-- Footer --}}
                <div class="dialog__footer">

                    <button
                        type="button"
                        class="button button--ghost button--neutral"
                        data-stisla-dialog-dismiss
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="button button--primary"
                    >
                        {{ $submitText }}
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>

