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
                @if (auth()->user()->role == 'admin')
                    <li class="sidebar-menu-item {{ request()->routeIs('user') ? 'active' : '' }}">
                        <a class="sidebar-menu-button" href="/user">
                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">people</i>
                            <span class="sidebar-menu-text">Pengguna</span>
                        </a>
                    </li>
                @endif

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

                <li class="sidebar-menu-item {{ request()->routeIs('aset') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="/aset">
                        <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">account_balance</i>
                        <span class="sidebar-menu-text">Aset</span>
                    </a>
                </li>

                {{-- <li class="sidebar-menu-item {{ request()->routeIs('rekening-kas') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="/rekening-kas">
                        <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">account_balance_wallet</i>
                        <span class="sidebar-menu-text">Rekening Kas</span>
                    </a>
                </li> --}}

                {{-- <li class="sidebar-menu-item {{ request()->routeIs('tahun-ajaran') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="/tahun-ajaran">
                        <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">event</i>
                        <span class="sidebar-menu-text">Tahun Ajaran</span>
                    </a>
                </li> --}}

            </ul>

            <div class="sidebar-heading">Manajemen</div>
            <div class="sidebar-block p-0">
                <ul class="sidebar-menu" id="components_menu">
                    <li class="sidebar-menu-item {{ request()->routeIs('penerimaan-dana') ? 'active' : '' }}">
                        <a class="sidebar-menu-button" href="/penerimaan-dana">
                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">attach_money</i>
                            <span class="sidebar-menu-text">Penerimaan Dana</span>
                        </a>
                    </li>

                    <li class="sidebar-menu-item {{ request()->routeIs('transaksi-harian') ? 'active' : '' }}">
                        <a class="sidebar-menu-button" href="/transaksi-harian">
                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">swap_horiz</i>
                            <span class="sidebar-menu-text">Transaksi Harian</span>
                        </a>
                    </li>

                    <li class="sidebar-menu-item {{ request()->routeIs('piutang') ? 'active' : '' }}">
                        <a class="sidebar-menu-button" href="/piutang">
                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">swap_horiz</i>
                            <span class="sidebar-menu-text">Piutang</span>
                        </a>
                    </li>

                    {{-- <li class="sidebar-menu-item {{ request()->routeIs('rekonsiliasi-bank') ? 'active' : '' }}">
                        <a class="sidebar-menu-button" href="/rekonsiliasi-bank">
                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">account_balance</i>
                            <span class="sidebar-menu-text">Rekonsiliasi Bank</span>
                        </a>
                    </li> --}}
                    <li class="sidebar-menu-item {{ request()->routeIs('penyesuaian') ? 'active' : '' }}">
                        <a class="sidebar-menu-button" href="/penyesuaian">
                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">autorenew</i>
                            <span class="sidebar-menu-text">Penyesuaian</span>
                        </a>
                    </li>

                    {{-- <li class="sidebar-menu-item {{ request()->routeIs('penyusutan') ? 'active' : '' }}">
                        <a class="sidebar-menu-button" href="/penyusutan">
                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">trending_down</i>
                            <span class="sidebar-menu-text">Penyusutan</span>
                        </a>
                    </li> --}}


                    {{-- <li class="sidebar-menu-item">
                        <a class="sidebar-menu-button" href="ui-buttons.html">
                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">business_center</i>
                            <span class="sidebar-menu-text">Forecasting</span>
                        </a>
                    </li> --}}
                </ul>
            </div>

            <div class="sidebar-heading">Report</div>
            <div class="sidebar-block p-0">
                <ul class="sidebar-menu" id="components_menu">
                    <li class="sidebar-menu-item {{ request()->routeIs('jurnal_umum') ? 'active' : '' }}">
                        <a class="sidebar-menu-button" href="/laporan/jurnal-umum">
                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">dns</i>
                            <span class="sidebar-menu-text">Jurnal Umum</span>
                        </a>
                    </li>

                    <li class="sidebar-menu-item {{ request()->routeIs('buku_besar') ? 'active' : '' }}">
                        <a class="sidebar-menu-button" href="/laporan/buku-besar">
                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">dns</i>
                            <span class="sidebar-menu-text">Buku Besar</span>
                        </a>
                    </li>

                    <li class="sidebar-menu-item {{ request()->routeIs('neraca_saldo') ? 'active' : '' }}">
                        <a class="sidebar-menu-button" href="/laporan/neraca-saldo">
                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">dns</i>
                            <span class="sidebar-menu-text">Neraca Saldo</span>
                        </a>
                    </li>

                    <li class="sidebar-menu-item {{ request()->routeIs('laba_rugi') ? 'active' : '' }}">
                        <a class="sidebar-menu-button" href="/laporan/laba-rugi">
                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">dns</i>
                            <span class="sidebar-menu-text">Laba Rugi</span>
                        </a>
                    </li>

                    <li class="sidebar-menu-item {{ request()->routeIs('neraca') ? 'active' : '' }}">
                        <a class="sidebar-menu-button" href="/laporan/neraca">
                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">dns</i>
                            <span class="sidebar-menu-text">Neraca</span>
                        </a>
                    </li>

                    <li class="sidebar-menu-item {{ request()->routeIs('penyusutan_aset') ? 'active' : '' }}">
                        <a class="sidebar-menu-button" href="/laporan/penyusutan-aset">
                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">dns</i>
                            <span class="sidebar-menu-text">Penyusutan Aset</span>
                        </a>
                    </li>

                    <li class="sidebar-menu-item {{ request()->routeIs('arus_kas') ? 'active' : '' }}">
                        <a class="sidebar-menu-button" href="/laporan/arus-kas">
                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">dns</i>
                            <span class="sidebar-menu-text">Arus Kas</span>
                        </a>
                    </li>

                    <li class="sidebar-menu-item {{ request()->routeIs('piutang_utang') ? 'active' : '' }}">
                        <a class="sidebar-menu-button" href="/laporan/piutang-utang">
                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">dns</i>
                            <span class="sidebar-menu-text">Piutang & Utang</span>
                        </a>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</div>
