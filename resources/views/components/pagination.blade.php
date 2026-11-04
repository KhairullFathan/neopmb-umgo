
@props(['paginator'])

@php
    $from = $paginator->firstItem() ?? 0;
    $to = $paginator->lastItem() ?? 0;
    $total = $paginator->total();

    $current = $paginator->currentPage();
    $last = $paginator->lastPage();

    // Menentukan rentang nomor halaman
    $start = max(1, $current - 2);
    $end = min($last, $current + 2);

    // Memastikan maksimal 5 halaman ditampilkan
    if ($end - $start < 4) {
        if ($start === 1) {
            $end = min($last, $start + 4);
        } elseif ($end === $last) {
            $start = max(1, $end - 4);
        }
    }
@endphp

<div class="card__footer">
    {{-- Data Information --}}
    <span class="text-xs text-muted-foreground">
        Showing {{ $from }}–{{ $to }} of {{ $total }}
    </span>

    @if ($paginator->hasPages())
        <nav class="ms-auto" aria-label="Pagination">
            <ul class="pagination">

                {{-- Previous --}}
                <li>
                    @if ($paginator->onFirstPage())
                        <span
                            class="pagination__button"
                            aria-disabled="true"
                            aria-label="Previous"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="1em"
                                height="1em"
                                viewBox="0 0 24 24"
                                aria-hidden="true"
                            >
                                <path
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.5"
                                    d="m15 5l-6 7l6 7"
                                />
                            </svg>
                        </span>
                    @else
                        <a
                            class="pagination__button"
                            href="{{ $paginator->previousPageUrl() }}"
                            aria-label="Previous"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="1em"
                                height="1em"
                                viewBox="0 0 24 24"
                                aria-hidden="true"
                            >
                                <path
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.5"
                                    d="m15 5l-6 7l6 7"
                                />
                            </svg>
                        </a>
                    @endif
                </li>

                {{-- First Page --}}
                @if ($start > 1)
                    <li>
                        <a
                            class="pagination__button"
                            href="{{ $paginator->url(1) }}"
                            aria-label="Go to page 1"
                        >
                            1
                        </a>
                    </li>

                    {{-- Left Ellipsis --}}
                    @if ($start > 2)
                        <li>
                            <span
                                class="pagination__button"
                                aria-hidden="true"
                            >
                                ...
                            </span>
                        </li>
                    @endif
                @endif

                {{-- Page Numbers --}}
                @for ($page = $start; $page <= $end; $page++)
                    <li>
                        @if ($page == $current)
                            <span
                                class="pagination__button"
                                data-state="active"
                                aria-current="page"
                            >
                                {{ $page }}
                            </span>
                        @else
                            <a
                                class="pagination__button"
                                href="{{ $paginator->url($page) }}"
                                aria-label="Go to page {{ $page }}"
                            >
                                {{ $page }}
                            </a>
                        @endif
                    </li>
                @endfor

                {{-- Last Page --}}
                @if ($end < $last)

                    {{-- Right Ellipsis --}}
                    @if ($end < $last - 1)
                        <li>
                            <span
                                class="pagination__button"
                                aria-hidden="true"
                            >
                                ...
                            </span>
                        </li>
                    @endif

                    <li>
                        <a
                            class="pagination__button"
                            href="{{ $paginator->url($last) }}"
                            aria-label="Go to page {{ $last }}"
                        >
                            {{ $last }}
                        </a>
                    </li>
                @endif

                {{-- Next --}}
                <li>
                    @if ($paginator->hasMorePages())
                        <a
                            class="pagination__button"
                            href="{{ $paginator->nextPageUrl() }}"
                            aria-label="Next"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="1em"
                                height="1em"
                                viewBox="0 0 24 24"
                                aria-hidden="true"
                            >
                                <path
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.5"
                                    d="m9 5l6 7l-6 7"
                                />
                            </svg>
                        </a>
                    @else
                        <span
                            class="pagination__button"
                            aria-disabled="true"
                            aria-label="Next"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="1em"
                                height="1em"
                                viewBox="0 0 24 24"
                                aria-hidden="true"
                            >
                                <path
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.5"
                                    d="m9 5l6 7l-6 7"
                                />
                            </svg>
                        </span>
                    @endif
                </li>

            </ul>
        </nav>
    @endif
</div>
