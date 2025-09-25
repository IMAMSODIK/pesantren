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
                                    <li class="breadcrumb-item active" aria-current="page">Laporan Jurnal Umum
                                </ol>
                            </nav>
                            <h3 class="m-0">Jurnal Umum</h3>
                        </div>
                        <div class="ml-auto">
                            {{-- <button class="btn btn-info" data-toggle="modal" data-target="#modal-large" type="button"
                                id="add-data">
                                + Tambah Penyesuaian
                            </button> --}}
                        </div>
                    </div>

                </div>
            </div>

            <div class="container-fluid page__container">

                <div class="card card-form d-flex flex-column flex-sm-row">
                    <div class="card-form__body card-body-form-group flex">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label for="start_date">Tanggal Mulai</label>
                                    <input type="date" id="start_date" class="form-control" value="{{ date('Y-m-01') }}">
                                </div>
                            </div>

                            <div class="col-lg-6 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label for="end_date">Tanggal Selesai</label>
                                    <input type="date" id="end_date" class="form-control" value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="ml-auto mr-3 mb-3 d-flex">
                                <button class="btn btn-success" id="btn_filter" style="margin-right: 5px">
                                    <i class="fa fa-filter me-2" aria-hidden="true"></i> Filter
                                </button>
                                <button class="btn btn-danger" id="btn_pdf" style="margin-right: 5px">
                                    <i class="fa fa-file-pdf"></i> Export PDF
                                </button>
                                <button class="btn btn-info" id="btn_csv">
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


                <div id="data_jurnal">
                    <table id="jurnalTable" class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Deskripsi</th>
                                <th>Akun</th>
                                <th class="text-end">Debit</th>
                                <th class="text-end">Kredit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rows as $row)
                                <tr>
                                    <td>{{ $row['tanggal'] }}</td>
                                    <td>{{ $row['deskripsi'] }}</td>
                                    <td>{{ $row['akun'] }}</td>
                                    <td class="text-end">{{ $row['debit'] }}</td>
                                    <td class="text-end">{{ $row['kredit'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada transaksi di periode ini</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>

            </div>

        </div>
        <!-- // END drawer-layout__content -->

        @include('layouts.sidebar')
    </div>
@endsection

@section('own-script')
    <script>
        $('#btn_filter').on('click', function(e) {
            e.preventDefault();
            let start_date = $('#start_date').val();
            let end_date = $('#end_date').val();

            if (start_date === "" || end_date === "") {
                alert("Pilih rentang tanggal terlebih dahulu!");
                return;
            }

            $.ajax({
                url: "/laporan/jurnal-umum/filter",
                type: "GET",
                data: {
                    start_date: start_date,
                    end_date: end_date
                },
                beforeSend: function() {
                    $('#btn_filter').html('<i class="fa fa-spinner fa-spin"></i> Loading...');
                },
                success: function(response) {
                    if ($.fn.DataTable.isDataTable('#jurnalTable')) {
                        $('#jurnalTable').DataTable().destroy();
                    }

                    $('#jurnalTable tbody').html(response);

                    if (!$("#jurnalTable tbody tr td").hasClass('data-empty')) {
                        $('#jurnalTable').DataTable({
                            paging: true,
                            searching: true,
                            ordering: true,
                            responsive: true,
                            language: {
                                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
                            }
                        });
                    }
                },
                error: function() {
                    alert("Terjadi kesalahan saat mengambil data.");
                },
                complete: function() {
                    $('#btn_filter').html('<i class="fa fa-filter me-2"></i> Filter');
                }
            });
        });


        $('#btn_pdf').on('click', function() {
            let startDate = $('#start_date').val();
            let endDate = $('#end_date').val();

            if (startDate === "" || endDate === "") {
                alert("Pilih rentang tanggal terlebih dahulu!");
                return;
            }

            window.open(`/laporan/jurnal-umum/pdf?start_date=${startDate}&end_date=${endDate}`, "_blank");
        });

        $('#btn_csv').on('click', function() {
            let startDate = $('#start_date').val();
            let endDate = $('#end_date').val();

            if (startDate === "" || endDate === "") {
                alert("Pilih rentang tanggal terlebih dahulu!");
                return;
            }

            window.open(`/laporan/jurnal-umum/csv?start_date=${startDate}&end_date=${endDate}`, "_blank");
        });
    </script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- jQuery (wajib) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#jurnalTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                responsive: true,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
                }
            });
        });
    </script>
@endsection
