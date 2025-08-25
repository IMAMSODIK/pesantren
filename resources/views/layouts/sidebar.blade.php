<div class="mdk-drawer  js-mdk-drawer" id="default-drawer" data-align="start">
    <div class="mdk-drawer__content">
        <div class="sidebar sidebar-light sidebar-left" data-perfect-scrollbar>

            <div class="sidebar-heading sidebar-m-t">DASHBOARD</div>
            <ul class="sidebar-menu">

                <li class="sidebar-menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('dashboard') }}">
                        <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">dashboard</i>
                        <span class="sidebar-menu-text">Dashboard</span>
                    </a>
                </li>
            </ul>

            <div class="sidebar-heading sidebar-m-t">MASTER</div>
            <ul class="sidebar-menu">

                <li class="sidebar-menu-item {{ request()->routeIs('user') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="/user">
                        <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">people</i>
                        <span class="sidebar-menu-text">Pengguna</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{ request()->routeIs('tipe-transaksi') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="/tipe-transaksi">
                        <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">swap_horiz</i>
                        <span class="sidebar-menu-text">Tipe Transaksi</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{ request()->routeIs('kategori-transaksi') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="/kategori-transaksi">
                        <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">view_compact</i>
                        <span class="sidebar-menu-text">Kategori Transaksi</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{ request()->routeIs('rekening-kas') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="/rekening-kas">
                        <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">account_balance_wallet</i>
                        <span class="sidebar-menu-text">Rekening Kas</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{ request()->routeIs('tahun-ajaran') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="/tahun-ajaran">
                        <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">event</i>
                        <span class="sidebar-menu-text">Tahun Ajaran</span>
                    </a>
                </li>

            </ul>

            <div class="sidebar-heading">Manajemen & Forecasting</div>
            <div class="sidebar-block p-0">
                <ul class="sidebar-menu" id="components_menu">
                    <li class="sidebar-menu-item">
                        <a class="sidebar-menu-button" href="company.html">
                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">swap_horiz</i>
                            <span class="sidebar-menu-text">Transaksi</span>
                        </a>
                    </li>


                    <li class="sidebar-menu-item">
                        <a class="sidebar-menu-button" href="ui-buttons.html">
                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">business_center</i>
                            <span class="sidebar-menu-text">Forecasting</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="sidebar-heading">Laporan</div>
            <div class="sidebar-block p-0">
                <ul class="sidebar-menu" id="components_menu">
                    <li class="sidebar-menu-item">
                        <a class="sidebar-menu-button" href="income.html">
                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">dns</i>
                            <span class="sidebar-menu-text">Laporan Keuangan</span>
                        </a>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</div>
