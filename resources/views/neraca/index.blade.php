@extends('layouts.template')

@section('content')
    <div class="mdk-drawer-layout js-mdk-drawer-layout" data-push data-responsive-width="992px">
        <div class="mdk-drawer-layout__content page">

            <div class="container-fluid page__heading-container">
                <div class="page__heading">
                    <div class="d-flex align-items-center">
                        <div>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Laporan Neraca
                                    </li>
                                </ol>
                            </nav>
                            <h3 class="m-0">Neraca</h3>
                        </div>
                        <div class="ml-auto d-flex gap-2">
                            <button class="btn btn-danger mr-2" id="btn_pdf">
                                <i class="fa fa-file-pdf"></i> Export PDF
                            </button>
                            <button class="btn btn-success" id="btn_csv">
                                <i class="fa fa-file-excel"></i> Export CSV
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid page__container">

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="start_date">Tanggal Awal</label>
                        <input type="date" id="start_date" class="form-control" value="{{ $start_date }}">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date">Tanggal Akhir</label>
                        <input type="date" id="end_date" class="form-control" value="{{ $end_date }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button class="btn btn-primary" id="btn_filter">Tampilkan</button>
                    </div>
                </div>


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
                                @forelse ($aset as $row)
                                    <tr>
                                        <td>{{ $row['nama'] }}</td>
                                        <td class="text-end">Rp. {{ number_format($row['saldo'], 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endforelse
                                <tr class="table-primary">
                                    <td><strong>Total Aset</strong></td>
                                    <td class="text-end"><strong>Rp.
                                            {{ number_format(array_sum(array_column($aset, 'saldo')), 0, ',', '.') }}</strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- ===== LIABILITAS + ASET NETO ===== -->
                    <div class="col-md-6">
                        <h4>Liabilitas dan Aset Neto</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Akun</th>
                                    <th class="text-end">Saldo</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- LIABILITAS --}}
                                <tr>
                                    <td colspan="2"><strong>Liabilitas</strong></td>
                                </tr>
                                @forelse ($liabilitas as $row)
                                    <tr>
                                        <td>{{ $row['nama'] }}</td>
                                        <td class="text-end">Rp. {{ number_format($row['saldo'], 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endforelse
                                <tr class="table-secondary">
                                    <td><strong>Total Liabilitas</strong></td>
                                    <td class="text-end"><strong>Rp.
                                            {{ number_format(array_sum(array_column($liabilitas, 'saldo')), 0, ',', '.') }}</strong>
                                    </td>
                                </tr>

                                {{-- ASET NETO --}}
                                <tr>
                                    <td colspan="2"><strong>Aset Neto</strong></td>
                                </tr>
                                @forelse ($asetNeto as $row)
                                    <tr>
                                        <td>{{ $row['nama'] }}</td>
                                        <td class="text-end">Rp. {{ number_format($row['saldo'], 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endforelse
                                <tr class="table-secondary">
                                    <td><strong>Total Aset Neto</strong></td>
                                    <td class="text-end"><strong>Rp.
                                            {{ number_format($totalAsetNeto, 0, ',', '.') }}</strong></td>
                                </tr>

                                <tr class="table-primary">
                                    <td><strong>Total Liabilitas + Aset Neto</strong></td>
                                    <td class="text-end"><strong>Rp.
                                            {{ number_format(array_sum(array_column($liabilitas, 'saldo')) + $totalAsetNeto, 0, ',', '.') }}</strong>
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
        function getFilterParams() {
            let start = $('#start_date').val();
            let end = $('#end_date').val();
            if (!start || !end) {
                alert("Isi tanggal terlebih dahulu!");
                return false;
            }
            return `?start_date=${start}&end_date=${end}`;
        }

        $('#btn_filter').on('click', function() {
            let params = getFilterParams();
            if (params) {
                window.location.href = `/laporan/neraca${params}`;
            }
        });

        $('#btn_pdf').on('click', function(e) {
            e.preventDefault();
            let params = getFilterParams();
            if (params) {
                window.location.href = `/laporan/neraca/pdf${params}`;
            }
        });

        $('#btn_csv').on('click', function(e) {
            e.preventDefault();
            let params = getFilterParams();
            if (params) {
                window.location.href = `/laporan/neraca/csv${params}`;
            }
        });
    </script>
@endsection
