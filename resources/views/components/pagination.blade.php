@php
    $from = $paginator->firstItem() ?? 0;
    $to = $paginator->lastItem() ?? 0;
    $total = $paginator->total();
@endphp

<div class="card__footer">
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

                {{-- Page Numbers --}}
                @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                    <li>
                        @if ($page == $paginator->currentPage())
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
                                href="{{ $url }}"
                            >
                                {{ $page }}
                            </a>
                        @endif
                    </li>
                @endforeach

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
