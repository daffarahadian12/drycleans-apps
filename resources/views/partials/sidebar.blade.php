<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">
                    <span>Main Menu</span>
                </li>
                
                <!-- Dashboard -->
                <li class="{{ request()->is('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <i class="feather-grid"></i> <span>Dashboard</span>
                    </a>
                </li>
                
                <!-- Pelanggan -->
                <li class="{{ request()->is('customers*') ? 'active' : '' }}">
                    <a href="{{ route('customers.index') }}">
                        <i class="fas fa-users"></i> <span>Pelanggan</span>
                    </a>
                </li>

                <!-- Transaksi -->
                <li class="{{ request()->is('transactions*') ? 'active' : '' }}">
                    <a href="{{ route('transactions.index') }}">
                        <i class="fas fa-receipt"></i> <span>Transaksi</span>
                    </a>
                </li>
                
                <!-- Paket Laundry -->
                <li class="{{ request()->is('packages*') ? 'active' : '' }}">
                    <a href="{{ route('packages.index') }}">
                        <i class="fas fa-box"></i> <span>Paket Laundry</span>
                    </a>
                </li>

                @if(auth()->user()->canManageUsers())
                <li class="menu-title">
                    <span>Management</span>
                </li>
                
                <!-- Pengguna -->
                <li class="{{ request()->is('users*') ? 'active' : '' }}">
                    <a href="{{ route('users.index') }}">
                        <i class="fas fa-user-tie"></i> <span>Pengguna</span>
                    </a>
                </li>
                
                <!-- Diskon -->
                <li class="{{ request()->is('discounts*') ? 'active' : '' }}">
                    <a href="{{ route('discounts.index') }}">
                        <i class="fas fa-tags"></i> <span>Diskon</span>
                    </a>
                </li>
                
                <!-- Laporan -->
                <li class="submenu {{ request()->is('reports*') ? 'active subdrop' : '' }}">
                    <a href="#"><i class="fas fa-chart-bar"></i> <span>Laporan</span> <span class="menu-arrow"></span></a>
                    <ul style="{{ request()->is('reports*') ? 'display: block;' : '' }}">
                        <li><a href="{{ route('reports.index') }}" class="{{ request()->is('reports') && !request()->is('reports/*') ? 'active' : '' }}">Dashboard Laporan</a></li>
                        <li><a href="{{ route('reports.transactions') }}" class="{{ request()->is('reports/transactions') ? 'active' : '' }}">Laporan Transaksi</a></li>
                        <li><a href="{{ route('reports.customers') }}" class="{{ request()->is('reports/customers') ? 'active' : '' }}">Laporan Pelanggan</a></li>
                        <li><a href="{{ route('reports.packages') }}" class="{{ request()->is('reports/packages') ? 'active' : '' }}">Laporan Paket</a></li>
                    </ul>
                </li>
                @endif
                
            </ul>
        </div>
    </div>
</div>
