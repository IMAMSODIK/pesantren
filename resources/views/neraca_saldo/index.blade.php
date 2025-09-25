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
                                    <li class="breadcrumb-item active" aria-current="page">Laporan Neraca Saldo
                                        {{ $bulan }}/{{ $tahun }}</li>
                                </ol>
                            </nav>
                            <h3 class="m-0">Neraca Saldo</h3>
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
                            <div class="col-md-6">
                                <label for="start_date">Tanggal Mulai</label>
                                <input type="date" id="start_date" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="end_date">Tanggal Akhir</label>
                                <input type="date" id="end_date" class="form-control">
                            </div>
                        </div>


                        <div class="row m-2">
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


                <div id="table-container">
                    @include('neraca_saldo.partials.table', ['data' => $data])
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
            let startDate = $('#start_date').val();
            let endDate = $('#end_date').val();

            if (startDate === "" || endDate === "") {
                alert("Pilih rentang tanggal terlebih dahulu!");
                return;
            }

            $.ajax({
                url: "/laporan/neraca-saldo/filter",
                type: "GET",
                data: {
                    start_date: startDate,
                    end_date: endDate
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
            let startDate = $('#start_date').val();
            let endDate = $('#end_date').val();

            if (startDate === "" || endDate === "") {
                alert("Pilih rentang tanggal terlebih dahulu!");
                return;
            }

            window.open(`/laporan/neraca-saldo/pdf?start_date=${startDate}&end_date=${endDate}`, '_blank');
        });

        $('#btn_csv').on('click', function(e) {
            e.preventDefault();
            let startDate = $('#start_date').val();
            let endDate = $('#end_date').val();

            if (startDate === "" || endDate === "") {
                alert("Pilih rentang tanggal terlebih dahulu!");
                return;
            }

            window.open(`/laporan/neraca-saldo/csv?start_date=${startDate}&end_date=${endDate}`, '_blank');
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
