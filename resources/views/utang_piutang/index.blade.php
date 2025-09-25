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
                                    <li class="breadcrumb-item active" aria-current="page">Laporan Utang Piutang</li>
                                </ol>
                            </nav>
                            <h3 class="m-0">Utang Piutang</h3>
                        </div>
                        <div class="ml-auto">
                            {{-- <button class="btn btn-danger" id="btn_pdf">
                                <i class="fa fa-file-pdf"></i> Export PDF
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
                                    <label for="end_date">Tanggal Akhir</label>
                                    <input type="date" id="end_date" class="form-control" value="{{ date('Y-m-t') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="ml-auto mr-3 mb-3 d-flex">
                                <button class="btn btn-success" id="btn_filter" style="margin-right: 5px">
                                    <i class="fa fa-filter me-2"></i> Filter
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
                    @include('utang_piutang.partials.table', [
                        'piutang' => $piutang,
                        'utang' => $utang,
                        'totalPiutang' => $totalPiutang,
                        'totalUtang' => $totalUtang,
                    ])
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
            let start_date = $('#start_date').val();
            let end_date = $('#end_date').val();

            if (start_date === "" || end_date === "") {
                alert("Pilih tanggal mulai dan tanggal akhir terlebih dahulu!");
                return;
            }

            $.ajax({
                url: "/laporan/piutang-utang/filter",
                type: "GET",
                data: {
                    start_date,
                    end_date
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
            let start_date = $('#start_date').val();
            let end_date = $('#end_date').val();
            window.location.href = `/laporan/piutang-utang/pdf?start_date=${start_date}&end_date=${end_date}`;
        });

        $('#btn_csv').on('click', function(e) {
            e.preventDefault();
            let start_date = $('#start_date').val();
            let end_date = $('#end_date').val();
            window.location.href = `/laporan/piutang-utang/csv?start_date=${start_date}&end_date=${end_date}`;
        });
    </script>
@endsection
