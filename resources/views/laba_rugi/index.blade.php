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
                                        Laporan Aktivitas
                                    </li>
                                </ol>
                            </nav>
                            <h3 class="m-0">Laporan Aktivitas</h3>
                        </div>
                        <div class="ml-auto">
                            {{-- Tombol tambahan bila perlu --}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid page__container">

                {{-- Filter --}}
                <div class="card card-form d-flex flex-column flex-sm-row">
                    <div class="card-form__body card-body-form-group flex">
                        {{-- <div class="row">
                            <div class="col-lg-6 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label for="filter_bulan">Pilih Bulan</label>
                                    <select id="filter_bulan" class="form-control">
                                        <option value="">-- Pilih Bulan --</option>
                                        @php $bulanSekarang = date('m'); @endphp
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}"
                                                {{ $bulanSekarang == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                                {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label for="filter_tahun">Pilih Tahun</label>
                                    <select id="filter_tahun" class="form-control">
                                        <option value="">-- Pilih Tahun --</option>
                                        @php $tahunSekarang = date('Y'); @endphp
                                        @for ($th = $tahunSekarang; $th >= 2020; $th--)
                                            <option value="{{ $th }}"
                                                {{ $th == $tahunSekarang ? 'selected' : '' }}>
                                                {{ $th }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div> --}}

                        {{-- Tambahan filter tanggal range --}}
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_awal">Tanggal Awal</label>
                                    <input type="date" id="tanggal_awal" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_akhir">Tanggal Akhir</label>
                                    <input type="date" id="tanggal_akhir" class="form-control">
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="ml-auto mr-3 mb-3 d-flex">
                                <button class="btn btn-success" id="btn_filter" style="margin-right: 5px">
                                    <i class="fa fa-filter me-2" aria-hidden="true"></i> Filter
                                </button>
                                <button class="btn btn-danger" id="btn_pdf" style="margin-right: 5px">
                                    <i class="fa fa-file-pdf me-2"></i> Export PDF
                                </button>
                                <button id="btn_csv" class="btn btn-info">
                                    <i class="fa fa-table"></i> Export CSV
                                </button>
                            </div>
                        </div>
                    </div>
                    <button
                        class="btn bg-white border-left border-top border-top-sm-0 rounded-top-0 rounded-top-sm rounded-left-sm-0"
                        onclick="location.reload()">
                        <i class="material-icons text-primary">refresh</i>
                    </button>
                </div>

                <div id="table-container">

                    {{-- Pendapatan Tidak Terikat --}}
                    <table class="table table-bordered table-sm mb-4">
                        <thead class="bg-dark">
                            <tr>
                                <th colspan="3" class="text-white text-center">Pendapatan Tidak Terikat</th>
                            </tr>
                            <tr>
                                <th class="text-white text-center">Kode Akun</th>
                                <th class="text-white text-center">Nama Akun</th>
                                <th class="text-white text-center">Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendapatanTidakTerikat as $row)
                                <tr>
                                    <td>{{ $row->kode }}</td>
                                    <td>{{ $row->nama }}</td>
                                    <td class="text-end">Rp {{ number_format($row->nominal, 2, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Tidak ada data</td>
                                </tr>
                            @endforelse
                            <tr class="fw-bold bg-light">
                                <td colspan="2">Total Pendapatan Tidak Terikat</td>
                                <td class="text-end">Rp {{ number_format($totalPendapatanTidakTerikat, 2, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>

                    {{-- Beban Tidak Terikat --}}
                    <table class="table table-bordered table-sm mb-4">
                        <thead class="bg-dark">
                            <tr>
                                <th colspan="3" class="text-center text-white">Beban Tidak Terikat</th>
                            </tr>
                            <tr>
                                <th class="text-white text-center">Kode Akun</th>
                                <th class="text-white text-center">Nama Akun</th>
                                <th class="text-white text-center" class="text-end">Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bebanTidakTerikat as $row)
                                <tr>
                                    <td>{{ $row->kode }}</td>
                                    <td>{{ $row->nama }}</td>
                                    <td class="text-end">Rp {{ number_format($row->nominal, 2, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Tidak ada data</td>
                                </tr>
                            @endforelse
                            <tr class="fw-bold bg-light">
                                <td colspan="2">Total Beban Tidak Terikat</td>
                                <td class="text-end">Rp {{ number_format($totalBebanTidakTerikat, 2, ',', '.') }}</td>
                            </tr>
                            <tr class="fw-bold bg-secondary text-white">
                                <td colspan="2">Surplus / Defisit Tidak Terikat</td>
                                <td class="text-end">Rp {{ number_format($surplusTidakTerikat, 2, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>

                    {{-- Pendapatan Terikat --}}
                    <table class="table table-bordered table-sm mb-4">
                        <thead class="bg-dark">
                            <tr>
                                <th colspan="3" class="text-center text-white">Pendapatan Terikat</th>
                            </tr>
                            <tr>
                                <th class="text-white text-center">Kode Akun</th>
                                <th class="text-white text-center">Nama Akun</th>
                                <th class="text-white text-center" class="text-end">Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendapatanTerikat as $row)
                                <tr>
                                    <td>{{ $row->kode }}</td>
                                    <td>{{ $row->nama }}</td>
                                    <td class="text-end">Rp {{ number_format($row->nominal, 2, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Tidak ada data</td>
                                </tr>
                            @endforelse
                            <tr class="fw-bold bg-light">
                                <td colspan="2">Total Pendapatan Terikat</td>
                                <td class="text-end">Rp {{ number_format($totalPendapatanTerikat, 2, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>

                    {{-- Beban Terikat --}}
                    <table class="table table-bordered table-sm mb-4">
                        <thead class="bg-dark">
                            <tr>
                                <th colspan="3" class="text-center text-white">Beban Terikat</th>
                            </tr>
                            <tr>
                                <th class="text-white text-center">Kode Akun</th>
                                <th class="text-white text-center">Nama Akun</th>
                                <th class="text-white text-center" class="text-end">Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bebanTerikat as $row)
                                <tr>
                                    <td>{{ $row->kode }}</td>
                                    <td>{{ $row->nama }}</td>
                                    <td class="text-end">Rp {{ number_format($row->nominal, 2, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Tidak ada data</td>
                                </tr>
                            @endforelse
                            <tr class="fw-bold bg-light">
                                <td colspan="2">Total Beban Terikat</td>
                                <td class="text-end">Rp {{ number_format($totalBebanTerikat, 2, ',', '.') }}</td>
                            </tr>
                            <tr class="fw-bold bg-secondary text-white">
                                <td colspan="2">Surplus / Defisit Terikat</td>
                                <td class="text-end">Rp {{ number_format($surplusTerikat, 2, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>

                    {{-- Total Perubahan Aset Neto --}}
                    <table class="table table-bordered table-sm">
                        <tfoot class="fw-bold bg-dark text-white">
                            <tr>
                                <td colspan="2" class="text-center">Perubahan Aset Neto</td>
                                <td class="text-end">Rp {{ number_format($surplusTotal, 2, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>

                </div>

            </div>

        </div>
        @include('layouts.sidebar')
    </div>
@endsection


@section('own-script')
    <script>
        $('#btn_filter').on('click', function(e) {
            e.preventDefault();
            let tanggal_awal = $('#tanggal_awal').val();
            let tanggal_akhir = $('#tanggal_akhir').val();

            if (tanggal_awal === "" || tanggal_akhir === "") {
                alert("Pilih tanggal awal & akhir terlebih dahulu!");
                return;
            }

            $.ajax({
                url: "/laporan/laporan-perubahan-aset-neto/filter",
                type: "GET",
                data: {
                    tanggal_awal: tanggal_awal,
                    tanggal_akhir: tanggal_akhir
                },
                beforeSend: function() {
                    $('#btn_filter').html('<i class="fa fa-spinner fa-spin"></i> Loading...');
                },
                success: function(response) {
                    $('#table-container').html(response);
                },
                error: function() {
                    alert("Terjadi kesalahan saat mengambil data.");
                },
                complete: function() {
                    $('#btn_filter').html('<i class="fa fa-filter me-2"></i> Filter');
                }
            });
        });

        $('#btn_pdf').on('click', function(e) {
            e.preventDefault();
            let tanggal_awal = $('#tanggal_awal').val();
            let tanggal_akhir = $('#tanggal_akhir').val();

            if (tanggal_awal === "" || tanggal_akhir === "") {
                alert("Pilih tanggal awal & akhir terlebih dahulu!");
                return;
            }

            window.location.href =
                `/laporan/laporan-perubahan-aset-neto/pdf?tanggal_awal=${tanggal_awal}&tanggal_akhir=${tanggal_akhir}`;
        });

        $('#btn_csv').on('click', function(e) {
            e.preventDefault();
            let tanggal_awal = $('#tanggal_awal').val();
            let tanggal_akhir = $('#tanggal_akhir').val();

            if (tanggal_awal === "" || tanggal_akhir === "") {
                alert("Pilih tanggal awal & akhir terlebih dahulu!");
                return;
            }

            window.location.href =
                `/laporan/laporan-perubahan-aset-neto/csv?tanggal_awal=${tanggal_awal}&tanggal_akhir=${tanggal_akhir}`;
        });
    </script>
@endsection
