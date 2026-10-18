@props(['menu'])
@php
    $hasChildren = $menu->children->isNotEmpty();
    $isActive = $menu->active_route
    ? request()->routeIs($menu->active_route)
    : ($menu->route
        ? request()->routeIs($menu->route)
        : false);
    foreach ($menu->children as $child) {
        $childIsActive = $child->route
            ? request()->routeIs($child->route)
            : false;

        if (!$childIsActive && $child->children->isNotEmpty()) {
            $checkChildren = function ($items) use (&$checkChildren) {
                foreach ($items as $item) {
                    if ($item->route && request()->routeIs($item->route)) {
                        return true;
                    }
                    if ($item->children->isNotEmpty() && $checkChildren($item->children)) {
                        return true;
                    }
                }
                return false;
            };
            $childIsActive = $checkChildren($child->children);
        }
        $isActive = $isActive || $childIsActive;
    }
@endphp

<li
    class="sidebar__item"
    @if ($hasChildren)
        data-state="{{ $isActive ? 'open' : 'closed' }}"
    @endif
>
    @if ($menu->route)
        <a
            class="sidebar__button"
            href="{{ route($menu->route) }}"
            @if ($isActive) aria-current="page" @endif
        >
            @include('components.sidebar-icon', ['icon' => $menu->icon])
            <span>{{ $menu->name }}</span>
        </a>
    @else
        <span class="sidebar__button">
            @include('components.sidebar-icon', ['icon' => $menu->icon])
            <span>{{ $menu->name }}</span>
        </span>
    @endif

    @if ($hasChildren)
        <button
            type="button"
            class="sidebar__item-action"
            data-stisla-sidebar-submenu-toggle
            aria-expanded="{{ $isActive ? 'true' : 'false' }}"
            aria-controls="nav-{{ $menu->key }}"
            aria-label="Toggle {{ $menu->name }} submenu"
        >
            <span class="sidebar__caret"></span>
        </button>

        <div class="sidebar__submenu" id="nav-{{ $menu->key }}">
            <ul class="sidebar__list">
                @foreach ($menu->children as $child)
                    <x-sidebar.menu-item :menu="$child" />
                @endforeach
            </ul>
        </div>
    @endif
</li>
