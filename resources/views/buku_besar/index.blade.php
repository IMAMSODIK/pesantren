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
                                    <li class="breadcrumb-item active" aria-current="page">Laporan Buku Besar
                                        {{ $bulan }}/{{ $tahun }}</li>
                                </ol>
                            </nav>
                            <h3 class="m-0">Buku Besar</h3>
                        </div>
                        <div class="ml-auto">
                        </div>
                    </div>

                </div>
            </div>

            <div class="container-fluid page__container">

                <div class="card card-form d-flex flex-column flex-sm-row">
                    <div class="card-form__body card-body-form-group flex">
                        <div class="row">
                            <!-- Start Date -->
                            <div class="col-lg-6 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label for="start_date">Tanggal Mulai</label>
                                    <input type="date" id="start_date" class="form-control" value="{{ date('Y-m-01') }}">
                                </div>
                            </div>

                            <!-- End Date -->
                            <div class="col-lg-6 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label for="end_date">Tanggal Akhir</label>
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


                <div id="buku_besar_container">
                    @include('buku_besar.partials.table', ['bukuBesar' => $bukuBesar])
                </div>

            </div>

        </div>
        <!-- // END drawer-layout__content -->

        @include('layouts.sidebar')
    </div>
@endsection

@section('own-script')
    <script>
        $("#btn_filter").on("click", function() {
            let startDate = $("#start_date").val();
            let endDate = $("#end_date").val();

            if (startDate === "" || endDate === "") {
                alert("Silakan pilih rentang tanggal terlebih dahulu!");
                return;
            }

            $.ajax({
                url: "/laporan/buku-besar/filter",
                type: "GET",
                data: {
                    start_date: startDate,
                    end_date: endDate
                },
                beforeSend: function() {
                    $("#buku_besar_container").html(
                        `<div class="alert alert-info text-center">Memuat data...</div>`
                    );
                },
                success: function(res) {
                    $("#buku_besar_container").html(res.html);
                },
                error: function() {
                    $("#buku_besar_container").html(
                        `<div class="alert alert-danger text-center">Gagal memuat data, coba lagi!</div>`
                    );
                }
            });
        });

        // Export PDF
        $("#btn_pdf").on("click", function() {
            let startDate = $("#start_date").val();
            let endDate = $("#end_date").val();

            if (startDate === "" || endDate === "") {
                alert("Silakan pilih rentang tanggal terlebih dahulu!");
                return;
            }

            window.location.href = `/laporan/buku-besar/pdf?start_date=${startDate}&end_date=${endDate}`;
        });

        $('#btn_csv').on('click', function() {
            let startDate = $('#start_date').val();
            let endDate = $('#end_date').val();

            if (startDate === "" || endDate === "") {
                alert("Pilih rentang tanggal terlebih dahulu!");
                return;
            }

            window.location.href = `/laporan/buku-besar/csv?start_date=${startDate}&end_date=${endDate}`;
        });
    </script>
@endsection
