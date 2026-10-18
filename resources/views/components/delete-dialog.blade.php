@props([
'id' => 'deleteDialog',
'title' => 'Hapus Data',
'description' => 'Data yang dihapus tidak dapat dikembalikan.',
'buttonText' => 'Hapus',
'cancelText' => 'Batal',
'method' => 'DELETE',
])

<div
    class="dialog dialog--sm"
    id="{{ $id }}"
    data-stisla-dialog
    data-state="closed"
    role="alertdialog"
    aria-modal="true"
    aria-labelledby="{{ $id }}Label"
    aria-describedby="{{ $id }}Desc"
    aria-hidden="true"
    tabindex="-1"
>
    <div class="dialog__backdrop" data-stisla-dialog-dismiss></div>

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
                    aria-hidden="true"
                >
                    <path d="M6 6l12 12M18 6L6 18" />
                </svg>
            </button>

            {{-- Body --}}
            <div class="dialog__body text-center pt-6">

                <span class="icon-box icon-box--danger icon-box--circle icon-box--lg mb-3">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="1em"
                        height="1em"
                        viewBox="0 0 24 24"
                        aria-hidden="true"
                    >
                        <g fill="none" stroke="currentColor" stroke-width="1.5">
                            <path
                                stroke-linecap="round"
                                d="M20.5 6h-17m15.333 2.5l-.46 6.9c-.177 2.654-.265 3.981-1.13 4.79s-2.196.81-4.856.81h-.774c-2.66 0-3.991 0-4.856-.81c-.865-.809-.954-2.136-1.13-4.79l-.46-6.9M9.5 11l.5 5m4.5-5l-.5 5"
                            />
                            <path
                                d="M6.5 6h.11a2 2 0 0 0 1.83-1.32l.034-.103l.097-.291c.083-.249.125-.373.18-.479a1.5 1.5 0 0 1 1.094-.788C9.962 3 10.093 3 10.355 3h3.29c.262 0 .393 0 .51.019a1.5 1.5 0 0 1 1.094.788l.097.291A2 2 0 0 0 17.5 6"
                            />
                        </g>
                    </svg>
                </span>

                <h3
                    class="dialog__title mb-1"
                    id="{{ $id }}Label"
                >
                    {{ $title }}
                    <span data-delete-name></span>?
                </h3>

                <p
                    class="text-muted-foreground"
                    id="{{ $id }}Desc"
                >
                    {{ $description }}
                </p>

            </div>

            {{-- Footer --}}
            <div class="dialog__footer justify-center">

                <button
                    type="button"
                    class="button button--ghost button--neutral"
                    data-stisla-dialog-dismiss
                >
                    {{ $cancelText }}
                </button>

                <form
                    method="POST"
                    action="#"
                    data-delete-form
                >
                    @csrf

                    @method($method)

                    <button
                        type="submit"
                        class="button button--danger"
                    >
                        {{ $buttonText }}
                    </button>
                </form>

            </div>

        </div>
    </div>

</div>

@once

<script>
document.addEventListener('click', function (event) {

    const button = event.target.closest(
        '[data-stisla-dialog-trigger]'
    );

    if (!button) {
        return;
    }

    const dialogId = button.getAttribute(
        'data-stisla-dialog-trigger'
    );

    const dialog = document.getElementById(dialogId);

    if (!dialog) {
        console.error(
            'Delete dialog tidak ditemukan:',
            dialogId
        );

        return;
    }

    const name = button.getAttribute(
        'data-delete-name'
    ) || '';

    const action = button.getAttribute(
        'data-delete-action'
    ) || '';

    const nameElement = dialog.querySelector(
        '[data-delete-name]'
    );

    const form = dialog.querySelector(
        '[data-delete-form]'
    );

    if (nameElement) {
        nameElement.textContent = name;
    }

    if (form) {
        form.setAttribute('action', action);
    }

});
</script>

@endonce

