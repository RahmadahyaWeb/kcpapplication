@php
    $menus = DB::table('menus')->orderBy('order_num')->get();

    $menuList = [];

    foreach ($menus as $menu) {
        if ($menu->parent_id) {
            $menuList[$menu->parent_id]['submenus'][] = $menu;
        } else {
            $menuList[$menu->id] = (array) $menu;
            $menuList[$menu->id]['submenus'] = [];
        }
    }
@endphp


<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="index.html" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('img/logo.png') }}" alt="logo-kcp" width="50">
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-2">KCP APP</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm d-flex align-items-center justify-content-center"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        @foreach ($menuList as $menu)

            @if ($menu['is_header'])
                <li class="menu-header small text-uppercase">
                    <span class="menu-header-text">{{ $menu['title'] }}</span>
                </li>
            @else
                @if (!empty($menu['submenus']))
                    <li class="menu-item">
                        <a href="{{ $menu['link'] }}" class="menu-link menu-toggle">
                            <i class="menu-icon {{ $menu['icon'] }}"></i>
                            <div class="text-truncate">{{ $menu['title'] }}</div>
                        </a>
                        <ul class="menu-sub">
                            @foreach ($menu['submenus'] as $submenu)
                                <li class="menu-item">
                                    <a href="{{ $submenu->link }}" class="menu-link">
                                        <div class="text-truncate">{{ $submenu->title }}</div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @else
                    <li class="menu-item">
                        <a href="{{ $menu['link'] }}" class="menu-link">
                            <i class="menu-icon {{ $menu['icon'] }}"></i>
                            <div class="text-truncate">{{ $menu['title'] }}</div>
                        </a>
                    </li>
                @endif
            @endif
        @endforeach
    </ul>
</aside>
