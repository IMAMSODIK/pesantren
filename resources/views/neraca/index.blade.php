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
                                    <li class="breadcrumb-item active" aria-current="page">Laporan Neraca
                                        {{ $bulan }}/{{ $tahun }}</li>
                                </ol>
                            </nav>
                            <h3 class="m-0">Neraca</h3>
                        </div>
                        <div class="ml-auto">
                            <button class="btn btn-danger" id="btn_pdf">
                                <i class="fa fa-file-pdf"></i> Export PDF
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="container-fluid page__container">

                <div class="row">
                    <!-- ===== ASET ===== -->
                    <div class="col-md-6">
                        <h4>Aset</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Akun</th>
                                    <th class="text-end">Saldo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="2"><strong>Aset Lancar</strong></td>
                                </tr>
                                @foreach ($asetList as $row)
                                    <tr>
                                        <td>{{ $row->nama }}</td>
                                        <td class="text-end">Rp. {{ number_format($row->saldo, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                <tr class="table-secondary">
                                    <td><strong>Total Aset Lancar</strong></td>
                                    <td class="text-end">
                                        <strong>Rp. {{ number_format($totalAsetLancar, 0, ',', '.') }}</strong></td>
                                </tr>

                                <tr>
                                    <td colspan="2"><strong>Aset Tetap</strong></td>
                                </tr>
                                @foreach ($asetTetapList as $row)
                                    <tr>
                                        <td>{{ $row->nama }}</td>
                                        <td class="text-end">Rp. {{ number_format($row->nilai_buku, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                <tr class="table-secondary">
                                    <td><strong>Total Aset Tetap</strong></td>
                                    <td class="text-end"><strong>Rp. {{ number_format($totalAsetTetap, 0, ',', '.') }}</strong>
                                    </td>
                                </tr>

                                <tr class="table-primary">
                                    <td><strong>Total Aset</strong></td>
                                    <td class="text-end"><strong>Rp. {{ number_format($totalAset, 0, ',', '.') }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- ===== LIABILITAS + EKUITAS ===== -->
                    <div class="col-md-6">
                        <h4>Liabilitas dan Ekuitas</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Akun</th>
                                    <th class="text-end">Saldo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="2"><strong>Liabilitas</strong></td>
                                </tr>
                                @foreach ($liabilitasList as $row)
                                    <tr>
                                        <td>{{ $row->nama }}</td>
                                        <td class="text-end">Rp. {{ number_format($row->saldo, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                <tr class="table-secondary">
                                    <td><strong>Total Liabilitas</strong></td>
                                    <td class="text-end">
                                        <strong>Rp. {{ number_format($totalLiabilitas, 0, ',', '.') }}</strong></td>
                                </tr>

                                <tr>
                                    <td colspan="2"><strong>Ekuitas</strong></td>
                                </tr>
                                @foreach ($ekuitasList as $row)
                                    <tr>
                                        <td>{{ $row->nama }}</td>
                                        <td class="text-end">Rp. {{ number_format($row->saldo, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                <tr class="table-secondary">
                                    <td><strong>Total Ekuitas</strong></td>
                                    <td class="text-end"><strong>Rp. {{ number_format($totalEkuitas, 0, ',', '.') }}</strong>
                                    </td>
                                </tr>

                                <tr class="table-primary">
                                    <td><strong>Total Liabilitas + Ekuitas</strong></td>
                                    <td class="text-end"><strong>Rp. {{ number_format($totalPassiva, 0, ',', '.') }}</strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>

        @include('layouts.sidebar')
    </div>
@endsection

@section('own-script')
    <script>
        $('#btn_pdf').on('click', function(e) {
            e.preventDefault();

            window.location.href = `/laporan/neraca/pdf`;
        });
    </script>
@endsection
