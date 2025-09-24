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
                                    <li class="breadcrumb-item active" aria-current="page">Transaksi</li>
                                </ol>
                            </nav>
                            <h3 class="m-0">Transaksi</h3>
                        </div>
                        <div class="ml-auto">
                            <button class="btn btn-info" data-toggle="modal" data-target="#modal-large" type="button"
                                id="add-data">
                                + Tambah Transaksi
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="container-fluid page__container">

                <div class="card card-form d-flex flex-column flex-sm-row">
                    <div class="card-form__body card-body-form-group flex">
                        <div class="row">
                            <div class="col-lg-3 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label for="filter_name">Cari Transaksi</label>
                                    <input id="filter_name" type="text" class="form-control"
                                        placeholder="Masukkan keyword">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label for="filter_buckets">Kategori Transaksi</label><br>
                                    <select id="filter_buckets" class="custom-select">
                                        <option value="all">Semua</option>
                                        @foreach ($kategoris as $kat)
                                            <option value="{{ $kat->id }}">{{ $kat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_mulai">Tanggal Mulai</label><br>
                                    <input type="date" class="custom-select" id="tanggal_mulai">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_akhir">Tanggal Akrhir</label><br>
                                    <input type="date" class="custom-select" id="tanggal_akhir">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="ml-auto mr-3 mb-3">
                                <button class="btn btn-success" id="btn_filter">
                                    <i class="fa fa-filter me-2" aria-hidden="true"></i> Filter
                                </button>
                            </div>
                        </div>
                    </div>
                    <button
                        class="btn bg-white border-left border-top border-top-sm-0 rounded-top-0 rounded-top-sm rounded-left-sm-0" onclick="location.reload()">
                        <i class="material-icons text-primary">refresh</i></button>
                </div>

                <div id="transaksi_list">
                    @foreach ($transaksis as $transaksi)
                        <div class="row align-items-center projects-item mb-1 transaksi" style="cursor: pointer"
                            data-id="{{ $transaksi->id }}">
                            <div class="col-sm-auto mb-1 mb-sm-0">
                                <div class="text-dark-gray">{{ $transaksi->format_tanggal }}</div>
                            </div>
                            <div class="col-sm">
                                <div class="card m-0">
                                    <div class="px-4 py-3">
                                        <div class="row align-items-center">
                                            <div class="col-md-6" style="min-width: 300px">
                                                <div class="d-flex align-items-center">
                                                    <a class="text-body">
                                                        <strong class="text-15pt mr-2">
                                                            {{ \Illuminate\Support\Str::limit($transaksi->deskripsi, 50, '...') }}
                                                        </strong>
                                                    </a>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <small class="text-dark-gray mr-2">Dibuat Oleh : </small>
                                                    <a class="d-flex align-items-middle">
                                                        <i
                                                            class="material-icons icon-muted icon-20pt mr-2">account_circle</i>
                                                        {{ $transaksi->createdBy->name }}
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-md-3 d-flex align-items-center">
                                                <span
                                                    class="badge badge-success">{{ $transaksi->kategoriTransaksi->name }}</span>
                                            </div>
                                            <div class="col-md-3 text-md-right" style="min-width: 140px;">
                                                <a class="text-body">{{ $transaksi->format_rupiah }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- <div class="mt-4">

                    <ul class="pagination justify-content-center ">

                        <li class="page-item disabled">
                            <a class="page-link" href="#" aria-label="Previous">
                                <span aria-hidden="true" class="material-icons">first_page</span>
                                <span class="sr-only">First</span>
                            </a>
                        </li>

                        <li class="page-item disabled">
                            <a class="page-link" href="#" aria-label="Previous">
                                <span aria-hidden="true" class="material-icons">chevron_left</span>
                                <span class="sr-only">Prev</span>
                            </a>
                        </li>

                        <li class="page-item active">
                            <a class="page-link" href="#" aria-label="1">
                                <span>1</span>
                            </a>
                        </li>

                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="2">
                                <span>2</span>
                            </a>
                        </li>

                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="3">
                                <span>3</span>
                            </a>
                        </li>

                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="4">
                                <span>4</span>
                            </a>
                        </li>

                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Next">
                                <span class="sr-only">Next</span>
                                <span aria-hidden="true" class="material-icons">chevron_right</span>
                            </a>
                        </li>

                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Next">
                                <span class="sr-only">Last</span>
                                <span aria-hidden="true" class="material-icons">last_page</span>
                            </a>
                        </li>

                    </ul>

                </div> --}}

            </div>

        </div>
        <!-- // END drawer-layout__content -->

        @include('layouts.sidebar')
    </div>
@endsection

@section('own-script')
    <script>
        $(document).ready(function() {
            $('#tabelKaryawan').DataTable();
        });

        $("#add-data").on("click", function() {
            let modal = $("#modal-large");
            modal.removeClass("fade");
            modal.addClass("show");
        });
    </script>


    <div id="modal-large" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-large-title">Tambah Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-12 col-md-12 mb-3">
                        <label for="tanggal">Tanggal Transaksi</label>
                        <input type="date" class="form-control" id="tanggal"
                            placeholder="Masukkan Tanggal Transaksi" required="">
                        <div class="invalid-feedback">Masukkan Tanggal Transaksi yang valid.</div>
                        <div class="valid-feedback"></div>
                    </div>

                    <div class="col-12 col-md-12 mb-3">
                        <label for="kategori">Kategori Transaksi</label>
                        <select class="form-control" id="kategori" required>
                            @foreach ($kategoris as $kat)
                                <option value="{{ $kat->id }}">{{ $kat->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Masukkan Kategori Transaksi yang valid.</div>
                        <div class="valid-feedback"></div>
                    </div>

                    <div class="col-12 col-md-12 mb-3">
                        <label for="nominal">Nominal Transaksi</label>
                        <input type="text" class="form-control format-currency" id="nominal"
                            placeholder="Masukkan Nominal Transaksi" required="">
                        <div class="invalid-feedback">Masukkan Nominal Transaksi yang valid.</div>
                        <div class="valid-feedback"></div>
                    </div>

                    <div class="col-12 col-md-12 mb-3">
                        <label for="deskripsi">Deskripsi Transaksi</label>
                        <textarea class="form-control" id="deskripsi" placeholder="Masukkan Deskripsi Transaksi" required=""
                            cols="5" rows="2"></textarea>
                        <div class="invalid-feedback">Masukkan Deskripsi Transaksi yang valid.</div>
                        <div class="valid-feedback"></div>
                    </div>

                    <div class="col-12 col-md-12 mb-3">
                        <label for="bukti">Bukti Transaksi</label>
                        <input type="file" class="form-control" id="bukti" placeholder="Masukkan Bukti Transaksi"
                            required="" accept=".jpg,.jpeg,.png,.pdf">
                        <div class="invalid-feedback">Masukkan Bukti Transaksi yang valid.</div>
                        <div class="valid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="store">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-large-edit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-large-title">Detail Transaksi</h5>
                    <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="" id="id">
                    <div class="row mb-3">
                        <div class="col-6 col-md-6">
                            <label for="creator">Dibuat Oleh</label>
                            <input type="text" class="form-control" id="creator" readonly>
                        </div>
                        <div class="col-6 col-md-6">
                            <label for="updator">Diupdate Oleh</label>
                            <input type="text" class="form-control" id="updator" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-12 mb-3">
                            <label for="edit_tanggal">Tanggal Transaksi</label>
                            <input type="date" class="form-control" id="edit_tanggal"
                                placeholder="Masukkan Tanggal Transaksi" required="">
                            <div class="invalid-feedback">Masukkan Tanggal Transaksi yang valid.</div>
                            <div class="valid-feedback"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-12 mb-3">
                            <label for="edit_kategori">Kategori Transaksi</label>
                            <select class="form-control" id="edit_kategori" required>
                                @foreach ($kategoris as $kat)
                                    <option value="{{ $kat->id }}">{{ $kat->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Masukkan Kategori Transaksi yang valid.</div>
                            <div class="valid-feedback"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-12 mb-3">
                            <label for="edit_nominal">Nominal Transaksi</label>
                            <input type="text" class="form-control format-currency" id="edit_nominal"
                                placeholder="Masukkan Nominal Transaksi" required="">
                            <div class="invalid-feedback">Masukkan Nominal Transaksi yang valid.</div>
                            <div class="valid-feedback"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-12 mb-3">
                            <label for="edit_deskripsi">Deskripsi Transaksi</label>
                            <textarea class="form-control" id="edit_deskripsi" placeholder="Masukkan Deskripsi Transaksi" required=""
                                cols="5" rows="2"></textarea>
                            <div class="invalid-feedback">Masukkan Deskripsi Transaksi yang valid.</div>
                            <div class="valid-feedback"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-12 mb-3">
                            <label for="edit_bukti">Bukti Transaksi</label>
                            <input type="file" class="form-control" id="edit_bukti"
                                placeholder="Masukkan Bukti Transaksi" required="" accept=".jpg,.jpeg,.png,.pdf">
                            <div class="invalid-feedback">Masukkan Bukti Transaksi yang valid.</div>
                            <div class="valid-feedback"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light close-modal" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="update">Update</button>
                    @if (auth()->user()->role == 'admin')
                        <button type="button" class="btn btn-danger text-white delete" data-toggle="modal"
                            data-target="#modal-warning">Delete</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div id="modal-warning" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-body text-center p-4">
                    <i class="material-icons icon-40pt text-warning mb-2">warning</i>
                    <h4>Anda yakin?</h4>
                    <p class="mt-3">Apakah anda yakin ingin menghapus data ini?</p>
                    <div class="d-flex justify-content-center gap-2 mt-3">
                        <button type="button" class="btn btn-secondary" style="margin-right: 10px"
                            data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-warning text-white" id="delete">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let modal = $("#modal-large"),
            button = "";
        $("#store").on("click", function(e) {
            e.preventDefault();
            modal = $("#modal-large"), button = $(this);
            closeModal(modal);
            buttonDisabled(button);

            let formData = new FormData();
            formData.append("tanggal", $("#tanggal").val());
            formData.append("kategori", $("#kategori").val());
            formData.append("nominal", getNumericValue($("#nominal").val()));
            formData.append("deskripsi", $("#deskripsi").val());
            formData.append("_token", $('meta[name="csrf-token"]').attr("content"));

            if ($("#bukti")[0].files.length > 0) {
                formData.append("bukti", $("#bukti")[0].files[0]);
            }

            $.ajax({
                url: "/penerimaan-dana/store",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    buttonEnabled(button);
                    if (response.status) {
                        showToastr('success', "Data berhasil disimpan!");
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        showToastr('warning', response.message);
                        openModal(modal);
                    }
                },
                error: function(response) {
                    buttonEnabled(button);
                    showToastr('warning', response.message);
                    openModal(modal);
                }
            });
        });

        $(document).on("click", ".transaksi", function() {
            modal = $("#modal-large-edit"),
                button = $(this);

            buttonDisabled(button);

            $.ajax({
                url: "/penerimaan-dana/edit",
                type: "GET",
                data: {
                    id: $(this).data('id')
                },
                success: function(response) {
                    buttonEnabled(button);
                    if (response.status) {
                        let data = response.data;
                        $("#id").val(data.id);
                        $("#edit_tanggal").val(data.tanggal);
                        $("#edit_kategori").val(data.kategori_transaksi_id);
                        $("#edit_nominal").val(formatRupiah(data.nominal || 0, 'Rp '));
                        $("#edit_deskripsi").val(data.deskripsi);
                        $("#creator").val(data.created_by.name);
                        $("#updator").val(data.updated_by.name);

                        if (data.bukti) {
                            $("#edit_bukti").siblings(".form-text").remove();

                            $("#edit_bukti").after(
                                `<div class="form-text mt-2">
                                    <a href="/storage/${data.bukti}" target="_blank" class="btn btn-sm btn-info">
                                        <i class="fas fa-file-alt"></i> Lihat Bukti Lama
                                    </a>
                                </div>`
                            );
                        }

                        openModal(modal);
                    } else {
                        showToastr('warning', response.message);
                    }
                },
                error: function(xhr) {
                    buttonEnabled(button);
                    showToastr('warning', xhr.responseJSON?.message || "Terjadi kesalahan");
                }
            });
        });

        $("#update").on("click", function(e) {
            e.preventDefault();
            modal = $("#modal-large-edit"), button = $(this);
            closeModal(modal);
            buttonDisabled(button);

            let formData = new FormData();
            formData.append("id", $("#id").val());
            formData.append("tanggal", $("#edit_tanggal").val());
            formData.append("kategori", $("#edit_kategori").val());
            formData.append("nominal", getNumericValue($("#edit_nominal").val()));
            formData.append("deskripsi", $("#edit_deskripsi").val());
            formData.append("_token", $('meta[name="csrf-token"]').attr("content"));

            if ($("#edit_bukti")[0].files.length > 0) {
                formData.append("bukti", $("#edit_bukti")[0].files[0]);
            }

            $.ajax({
                url: "/penerimaan-dana/update",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    buttonEnabled(button);
                    if (response.status) {
                        showToastr("success", "Data berhasil diperbaharui!");
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        showToastr("warning", response.message);
                        openModal(modal);
                    }
                },
                error: function(xhr) {
                    buttonEnabled(button);
                    showToastr("warning", "Terjadi kesalahan sistem!");
                    openModal(modal);
                }
            });
        });

        $(document).on("click", ".delete", function() {
            closeModal(modal);
            let id = $("#id").val();
            $("#delete").attr("data-id", id);
        });

        $("#delete").on("click", function() {
            button = $(this);
            buttonDisabled(button);

            $.ajax({
                url: "/penerimaan-dana/delete",
                type: "POST",
                data: {
                    id: $(this).data('id'),
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    buttonEnabled(button);
                    if (response.status) {
                        showToastr('success', "Data berhasil Dinonaktifkan!");
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        showToastr('warning', response.message);
                    }
                },
                error: function(response) {
                    buttonEnabled(button);
                    showToastr('warning', response.message);
                }
            });
        });

        $(".close-modal").on("click", function() {
            closeModal(modal);
        })

        $(document).ready(function() {
            let typingTimer;
            let doneTypingInterval = 1;

            $("#filter_name").on("keyup", function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(function() {
                    loadTransaksi();
                }, doneTypingInterval);
            });

            $("#btn_filter").on("click", function(e) {
                e.preventDefault();
                loadTransaksi();
            });

            function loadTransaksi() {
                $.ajax({
                    url: "/penerimaan-dana/search",
                    type: "GET",
                    data: {
                        name: $("#filter_name").val(),
                        kategori: $("#filter_buckets").val(),
                        tanggal_mulai: $("#tanggal_mulai").val(),
                        tanggal_akhir: $("#tanggal_akhir").val()
                    },
                    beforeSend: function() {
                        $("#transaksi_list").html("<p class='text-center'>Memuat data...</p>");
                    },
                    success: function(res) {
                        $("#transaksi_list").html(res.html);
                    },
                    error: function() {
                        $("#transaksi_list").html(
                            "<p class='text-center text-danger'>Gagal memuat data</p>");
                    }
                });
            }
        });

        function getNumericValue(rupiahValue) {
            if (rupiahValue === null || rupiahValue === undefined) return 0;

            let s = String(rupiahValue).trim();

            s = s.replace(/[^0-9\.,]/g, '');

            if (s === '' || s === '-') return 0;

            if (s.indexOf(',') !== -1) {
                s = s.replace(',', '.');
            }

            let parts = s.split('.');
            if (parts.length > 1) {
                if (parts[parts.length - 1].length <= 2) {
                    s = parts.slice(0, -1).join('') + '.' + parts[parts.length - 1];
                } else {
                    s = s.replace(/\./g, '');
                }
            }

            let num = parseFloat(s);
            return isNaN(num) ? 0 : num;
        }

        function formatRupiah(angka, prefix = '') {
            if (angka === null || angka === undefined || angka === '') {
                return prefix ? prefix + '0' : '0';
            }

            let num;
            if (typeof angka === 'number') {
                num = angka;
            } else {
                num = getNumericValue(angka);
            }

            let formatted;
            if (Math.abs(Math.round(num) - num) > 0) {
                formatted = num.toLocaleString('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            } else {
                formatted = num.toLocaleString('id-ID', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                });
            }

            return prefix ? prefix + formatted : formatted;
        }

        $(".format-currency").on("keyup", function() {
            $(this).val(formatRupiah($(this).val(), 'Rp '));
        });
    </script>
@endsection
