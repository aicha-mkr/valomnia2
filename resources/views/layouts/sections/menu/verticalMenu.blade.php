<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

  <!-- Hide app brand if navbar-full -->
  <div class="app-brand demo">
    <a href="{{ url('/') }}" class="app-brand-link">
      <span class="app-brand-logo demo">@include('_partials.macros', ["width" => 25, "withbg" => 'var(--bs-primary)'])</span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm d-flex align-items-center justify-content-center"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    @foreach ($menuData[0]->menu as $menu)

      {{-- Adding active and open class if child is active --}}

      {{-- Menu headers --}}
      @if (isset($menu->menuHeader))
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">{{ __($menu->menuHeader) }}</span>
        </li>
      @else

      {{-- Active menu method --}}
      @php
      $activeClass = null;
      $currentRouteName = Route::currentRouteName();

      if ($currentRouteName === $menu->slug) {
        $activeClass = 'active';
      } elseif (isset($menu->submenu)) {
        if (gettype($menu->slug) === 'array') {
          foreach ($menu->slug as $slug) {
            if (str_contains($currentRouteName, $slug) && strpos($currentRouteName, $slug) === 0) {
              $activeClass = 'active open';
            }
          }
        } else {
          if (str_contains($currentRouteName, $menu->slug) && strpos($currentRouteName, $menu->slug) === 0) {
            $activeClass = 'active open';
          }
        }
      }
      @endphp

      {{-- Main menu --}}
      <li class="menu-item {{ $activeClass }}">
        <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}" class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}" @if (isset($menu->target) && !empty($menu->target)) target="_blank" @endif>
          @isset($menu->icon)
            <i class="{{ $menu->icon }}"></i>
          @endisset
          <div>{{ isset($menu->name) ? __($menu->name) : '' }}</div>
          @isset($menu->badge)
            <div class="badge rounded-pill bg-{{ $menu->badge[0] }} text-uppercase ms-auto">{{ $menu->badge[1] }}</div>
          @endisset
        </a>

        {{-- Submenu --}}
        @isset($menu->submenu)
          @include('layouts.sections.menu.submenu', ['menu' => $menu->submenu])
        @endisset
      </li>
      @endif
    @endforeach

    <!-- Email Template Manager Item with Submenu -->
    <li class="menu-item">
      <a href="{{ route('email.liste') }}" class="menu-link menu-toggle">
        <i class="bx bx-envelope me-2"></i> <!-- Icon with margin -->
        <div>Email Templates</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item">
          <a href="{{ route('email.liste') }}" class="menu-link">
             <!-- Plus icon for creating -->
            <div>List Email Template</div>
          </a>
        </li>
        <li class="menu-item">
          <a href="{{ route('email.create') }}" class="menu-link">
             <!-- Plus icon for creating -->
            <div>Create Email Template</div>
          </a>
        </li>
      </ul>
    </li>

  </ul>

</aside>