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
                            <!-- Filter Bulan -->
                            <!-- Filter Bulan -->
                            <div class="col-lg-6 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label for="filter_bulan">Pilih Bulan</label>
                                    <select id="filter_bulan" class="form-control">
                                        <option value="">-- Pilih Bulan --</option>
                                        @php $bulanSekarang = date('m'); @endphp
                                        <option value="01" {{ $bulanSekarang == '01' ? 'selected' : '' }}>Januari
                                        </option>
                                        <option value="02" {{ $bulanSekarang == '02' ? 'selected' : '' }}>Februari
                                        </option>
                                        <option value="03" {{ $bulanSekarang == '03' ? 'selected' : '' }}>Maret</option>
                                        <option value="04" {{ $bulanSekarang == '04' ? 'selected' : '' }}>April</option>
                                        <option value="05" {{ $bulanSekarang == '05' ? 'selected' : '' }}>Mei</option>
                                        <option value="06" {{ $bulanSekarang == '06' ? 'selected' : '' }}>Juni</option>
                                        <option value="07" {{ $bulanSekarang == '07' ? 'selected' : '' }}>Juli</option>
                                        <option value="08" {{ $bulanSekarang == '08' ? 'selected' : '' }}>Agustus
                                        </option>
                                        <option value="09" {{ $bulanSekarang == '09' ? 'selected' : '' }}>September
                                        </option>
                                        <option value="10" {{ $bulanSekarang == '10' ? 'selected' : '' }}>Oktober
                                        </option>
                                        <option value="11" {{ $bulanSekarang == '11' ? 'selected' : '' }}>November
                                        </option>
                                        <option value="12" {{ $bulanSekarang == '12' ? 'selected' : '' }}>Desember
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Filter Tahun -->
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

                        </div>

                        <div class="row">
                            <div class="ml-auto mr-3 mb-3 d-flex">
                                <button class="btn btn-success" id="btn_filter" style="margin-right: 5px">
                                    <i class="fa fa-filter me-2" aria-hidden="true"></i> Filter
                                </button>
                                <button class="btn btn-danger" id="btn_pdf">
                                    <i class="fa fa-file-pdf"></i> Export PDF
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
            let bulan = $("#filter_bulan").val();
            let tahun = $("#filter_tahun").val();

            if (bulan === "" || tahun === "") {
                alert("Silakan pilih bulan dan tahun terlebih dahulu!");
                return;
            }

            $.ajax({
                url: "/laporan/buku-besar/filter",
                type: "GET",
                data: {
                    bulan: bulan,
                    tahun: tahun
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
            let bulan = $("#filter_bulan").val();
            let tahun = $("#filter_tahun").val();

            if (bulan === "" || tahun === "") {
                alert("Silakan pilih bulan dan tahun terlebih dahulu!");
                return;
            }

            window.location.href = `/laporan/buku-besar/pdf?bulan=${bulan}&tahun=${tahun}`;
        });
    </script>
@endsection
