<div class="mdk-drawer  js-mdk-drawer" id="default-drawer" data-align="start">
    <div class="mdk-drawer__content">
        <div class="sidebar sidebar-light sidebar-left" data-perfect-scrollbar>

            <div class="sidebar-heading sidebar-m-t">MASTER</div>
            <ul class="sidebar-menu">

                <li class="sidebar-menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('dashboard') }}">
                        <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">dashboard</i>
                        <span class="sidebar-menu-text">Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{ request()->routeIs('user') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="/user">
                        <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">people</i>
                        <span class="sidebar-menu-text">Pengguna</span>
                    </a>
                </li>

                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="services.html">
                        <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">dns</i>
                        <span class="sidebar-menu-text">Layanan</span>
                    </a>
                </li>
            </ul>
            <div class="sidebar-heading">Kontrak & Penugasan</div>
            <div class="sidebar-block p-0">
                <ul class="sidebar-menu" id="components_menu">
                    <li class="sidebar-menu-item">
                        <a class="sidebar-menu-button" href="company.html">
                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">business</i>
                            <span class="sidebar-menu-text">Clients</span>
                        </a>
                    </li>


                    <li class="sidebar-menu-item">
                        <a class="sidebar-menu-button" href="ui-buttons.html">
                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">business_center</i>
                            <span class="sidebar-menu-text">Penugasan</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="sidebar-heading">Keuangan</div>
            <div class="sidebar-block p-0">
                <ul class="sidebar-menu" id="components_menu">
                    <li class="sidebar-menu-item">
                        <a class="sidebar-menu-button" href="income.html">
                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">account_balance_wallet</i>
                            <span class="sidebar-menu-text">Pemasukan</span>
                        </a>
                    </li>

                    <li class="sidebar-menu-item">
                        <a class="sidebar-menu-button" href="expense.html">
                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">trending_down</i>
                            <span class="sidebar-menu-text">Pengeluaran</span>
                        </a>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</div>
