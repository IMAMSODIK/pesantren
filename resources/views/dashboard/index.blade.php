@extends('layouts.template')

@section('content')
    <div class="mdk-drawer-layout js-mdk-drawer-layout" data-push data-responsive-width="992px">
        <div class="mdk-drawer-layout__content page">

            <div class="container-fluid  page__heading-container">
                <div class="page__heading">

                    <div class="d-flex align-items-center">
                        <div>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                                </ol>
                            </nav>
                            <h1 class="m-0">Dashboard</h1>
                        </div>
                        <div class="ml-auto">
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid page__container">
                <div class="row card-group-row">
                    <div class="col-lg-4 col-md-4 card-group-row__col">
                        <div class="card card-group-row__card card-shadow">
                            <div class="p-2 d-flex flex-row align-items-center">
                                <div class="avatar avatar-xs mr-2">
                                    <span class="avatar-title rounded-circle text-center bg-danger">
                                        <i class="material-icons text-white icon-18pt">info</i>
                                    </span>
                                </div>
                                <a href="/" class="text-dark">
                                    <strong>Tentang Aplikasi</strong>
                                </a>
                            </div>
                        </div>
                    </div>
                    @if (auth()->user()->role == 'admin')
                        <div class="col-lg-4 col-md-4 card-group-row__col">
                            <div class="card card-group-row__card card-shadow">
                                <div class="p-2 d-flex flex-row align-items-center">
                                    <div class="avatar avatar-xs mr-2">
                                        <span class="avatar-title rounded-circle text-center">
                                            <i class="material-icons text-white icon-18pt">person</i>
                                        </span>
                                    </div>
                                    <a href="/user" class="text-dark">
                                        <strong>Pengguna</strong>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-lg-4 col-md-4 card-group-row__col">
                        <div class="card card-group-row__card card-shadow">
                            <div class="p-2 d-flex flex-row align-items-center">
                                <div class="avatar avatar-xs mr-2">
                                    <span class="avatar-title rounded-circle text-center bg-warning">
                                        <i class="material-icons text-white icon-18pt">account_balance</i>
                                    </span>
                                </div>
                                <a href="/aset" class="text-dark">
                                    <strong>Aset</strong>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-4 card-group-row__col">
                        <div class="card card-group-row__card card-shadow">
                            <div class="p-2 d-flex flex-row align-items-center">
                                <div class="avatar avatar-xs mr-2">
                                    <span class="avatar-title rounded-circle text-center bg-primary">
                                        <i class="material-icons text-white icon-18pt">assignment</i>
                                    </span>
                                </div>
                                <a href="/laporan/jurnal-umum" class="text-dark">
                                    <strong>Jurnal Umum</strong>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-4 card-group-row__col">
                        <div class="card card-group-row__card card-shadow">
                            <div class="p-2 d-flex flex-row align-items-center">
                                <div class="avatar avatar-xs mr-2">
                                    <span class="avatar-title rounded-circle text-center bg-primary">
                                        <i class="material-icons text-white icon-18pt">assignment</i>
                                    </span>
                                </div>
                                <a href="/laporan/buku-besar" class="text-dark">
                                    <strong>Buku Besar</strong>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-4 card-group-row__col">
                        <div class="card card-group-row__card card-shadow">
                            <div class="p-2 d-flex flex-row align-items-center">
                                <div class="avatar avatar-xs mr-2">
                                    <span class="avatar-title rounded-circle text-center bg-primary">
                                        <i class="material-icons text-white icon-18pt">assignment</i>
                                    </span>
                                </div>
                                <a href="/laporan/neraca-saldo" class="text-dark">
                                    <strong>Neraca Saldo</strong>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-4 card-group-row__col">
                        <div class="card card-group-row__card card-shadow">
                            <div class="p-2 d-flex flex-row align-items-center">
                                <div class="avatar avatar-xs mr-2">
                                    <span class="avatar-title rounded-circle text-center bg-primary">
                                        <i class="material-icons text-white icon-18pt">assignment</i>
                                    </span>
                                </div>
                                <a href="/laporan/laba-rugi" class="text-dark">
                                    <strong>Laba Rugi</strong>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-4 card-group-row__col">
                        <div class="card card-group-row__card card-shadow">
                            <div class="p-2 d-flex flex-row align-items-center">
                                <div class="avatar avatar-xs mr-2">
                                    <span class="avatar-title rounded-circle text-center bg-primary">
                                        <i class="material-icons text-white icon-18pt">assignment</i>
                                    </span>
                                </div>
                                <a href="/laporan/neraca" class="text-dark">
                                    <strong>Neraca</strong>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-4 card-group-row__col">
                        <div class="card card-group-row__card card-shadow">
                            <div class="p-2 d-flex flex-row align-items-center">
                                <div class="avatar avatar-xs mr-2">
                                    <span class="avatar-title rounded-circle text-center bg-primary">
                                        <i class="material-icons text-white icon-18pt">assignment</i>
                                    </span>
                                </div>
                                <a href="/laporan/penyusutan-aset" class="text-dark">
                                    <strong>Penyusutan Aset</strong>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-4 card-group-row__col">
                        <div class="card card-group-row__card card-shadow">
                            <div class="p-2 d-flex flex-row align-items-center">
                                <div class="avatar avatar-xs mr-2">
                                    <span class="avatar-title rounded-circle text-center bg-primary">
                                        <i class="material-icons text-white icon-18pt">assignment</i>
                                    </span>
                                </div>
                                <a href="/laporan/arus-kas" class="text-dark">
                                    <strong>Arus Kas</strong>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-4 card-group-row__col">
                        <div class="card card-group-row__card card-shadow">
                            <div class="p-2 d-flex flex-row align-items-center">
                                <div class="avatar avatar-xs mr-2">
                                    <span class="avatar-title rounded-circle text-center bg-primary">
                                        <i class="material-icons text-white icon-18pt">assignment</i>
                                    </span>
                                </div>
                                <a href="/laporan/piutang-utang" class="text-dark">
                                    <strong>Piutang Utang</strong>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                </div>

            </div>

        </div>
        <!-- // END drawer-layout__content -->

        @include('layouts.sidebar')
    </div>
@endsection
