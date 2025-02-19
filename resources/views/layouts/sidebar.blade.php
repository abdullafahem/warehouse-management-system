<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/') }}">
        <div class="sidebar-brand-icon">
            <x-application-logo class="w-10 h-10 fill-current text-white" />
        </div>
        <div class="sidebar-brand-text mx-3">{{ env('APP_SHORT_NAME') }}</div>
    </a>
    <hr class="sidebar-divider">
    
    @if(auth()->user()->role->value == 'WAREHOUSE_MANAGER' || auth()->user()->role->value == 'SYSTEM_ADMIN')
    <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span class="wms-nav">Dashboard</span>
        </a>
    </li>
    <li class="nav-item {{ request()->is('warehouse/orders*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('warehouse.orders') }}">
            <i class="fas fa-fw fa-box"></i>
            <span class="wms-nav">Orders</span>
        </a>
    </li>
    <li class="nav-item {{ request()->is('warehouse/inventory*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('warehouse.inventory') }}">
            <i class="fas fa-fw fa-warehouse"></i>
            <span class="wms-nav">Inventory Items</span>
        </a>
    </li>
    <li class="nav-item {{ request()->is('warehouse/trucks*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('warehouse.trucks') }}">
            <i class="fas fa-fw fa-truck"></i>
            <span class="wms-nav">Trucks</span>
        </a>
    </li>
    @elseif(auth()->user()->role->value == 'CLIENT')
    <li class="nav-item {{ request()->is('orders*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('orders.index') }}">
            <i class="fas fa-fw fa-box"></i>
            <span class="wms-nav">Orders</span>
        </a>
    </li>
    @endif
    @if(auth()->user()->role->value == 'SYSTEM_ADMIN')
    <li class="nav-item {{ request()->is('users/index*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('users.index') }}">
            <i class="fas fa-fw fa-user"></i>
            <span class="wms-nav">Users</span>
        </a>
    </li>
    @endif
</ul>
