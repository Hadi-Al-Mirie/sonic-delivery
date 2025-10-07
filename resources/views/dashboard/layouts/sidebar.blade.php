<div id="app-sidepanel" class="app-sidepanel">
    <div id="sidepanel-drop" class="sidepanel-drop"></div>
    <div class="sidepanel-inner d-flex flex-column">
        <a href="#" id="sidepanel-close" class="sidepanel-close d-xl-none">&times;</a>
        <div class="app-branding">
            <a class="app-logo" href="index.html"><img class="logo-icon me-2"
                    src="{{ asset('assets/images/app-logo.jpg') }}" alt="logo"><span
                    class="logo-text">SonigDlivry</span></a>

        </div>

        <nav id="app-nav-main" class="app-nav app-nav-main flex-grow-1">
            <ul class="app-menu list-unstyled accordion" id="menu-accordion">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                        href="{{ route('admin.dashboard') }}">
                        <span class="nav-icon">
                            <i class="fas fa-tachometer-alt"></i>
                        </span>
                        <span class="nav-link-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}"
                        href="{{ route('admin.users.index') }}">
                        <span class="nav-icon">
                            <i class="fas fa-user-alt"></i>
                        </span>
                        <span class="nav-link-text">Users</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.orders.index') ? 'active' : '' }}"
                        href="{{ route('admin.orders.index') }}">
                        <span class="nav-icon">
                            <i class="fas fa-truck"></i>
                        </span>
                        <span class="nav-link-text">Orders</span>
                    </a>
                </li>
                <li class="nav-item has-submenu">
                    <a class="nav-link submenu-toggle {{ request()->routeIs('admin.stores.index') || request()->routeIs('admin.stores.create') ? 'active' : '' }}"
                        href="#" data-bs-toggle="collapse" data-bs-target="#submenu-1" aria-expanded="false"
                        aria-controls="submenu-1">
                        <span class="nav-icon">
                            <i class="fas fa-store"></i>
                        </span>
                        <span class="nav-link-text">Stores</span>
                        <span class="submenu-arrow">
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chevron-down"
                                fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                            </svg>
                        </span>
                    </a>
                    <div id="submenu-1" class="collapse submenu submenu-1" data-bs-parent="#menu-accordion">
                        <ul class="submenu-list list-unstyled">
                            <li class="submenu-item"><a class="submenu-link"
                                    href="{{ route('admin.stores.index') }}">All Stores</a></li>
                            <li class="submenu-item"><a class="submenu-link"
                                    href="{{ route('admin.stores.create') }}">Add Store</a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item has-submenu">
                    <a class="nav-link submenu-toggle {{ request()->routeIs('admin.products.index') || request()->routeIs('admin.products.create') ? 'active' : '' }}"
                        href="#" data-bs-toggle="collapse" data-bs-target="#submenu-2" aria-expanded="false"
                        aria-controls="submenu-2">
                        <span class="nav-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </span>
                        <span class="nav-link-text">Products</span>
                        <span class="submenu-arrow">
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chevron-down"
                                fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                            </svg>
                        </span>
                    </a>
                    <div id="submenu-2" class="collapse submenu submenu-2" data-bs-parent="#menu-accordion">
                        <ul class="submenu-list list-unstyled">
                            <li class="submenu-item"><a class="submenu-link"
                                    href="{{ route('admin.products.index') }}">All Products</a></li>
                            <li class="submenu-item"><a class="submenu-link"
                                    href="{{ route('admin.products.create') }}">Add Product</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </nav>


    </div>
</div>
