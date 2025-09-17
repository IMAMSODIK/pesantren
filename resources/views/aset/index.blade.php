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
                                    <li class="breadcrumb-item active" aria-current="page">Aset</li>
                                </ol>
                            </nav>
                            <h3 class="m-0">Aset</h3>
                        </div>
                        <div class="ml-auto">
                            <button class="btn btn-info" data-toggle="modal" data-target="#modal-large" type="button"
                                id="add-data">
                                + Tambah Aset
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="container-fluid page__container">
                <div class="row card-group-row">
                    <div class="col-12 card-group-row__col">
                        <div class="card card-group-row__card card-shadow">
                            <div class="p-2 d-flex flex-row align-items-center">
                                <div class="container" width="100%">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h3></h3>

                                    </div>

                                    <div class="table-responsive">
                                        <table id="tabelKaryawan" class="table table-striped table-bordered align-middle">
                                            <thead class="table">
                                                <tr>
                                                    <th class="text-center">ID</th>
                                                    <th class="text-center">Nama Aset</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $no = 1;
                                                @endphp
                                                @foreach ($asets as $aset)
                                                    <tr style="cursor: pointer" class="row-data" data-id="{{$aset->id}}">
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ $aset->nama }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
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
                        <label for="nama">Nama Aset</label>
                        <input type="text" class="form-control" id="nama" placeholder="Masukkan Nama Aset"
                            required="">
                        <div class="invalid-feedback">Masukkan Nama Aset yang valid.</div>
                        <div class="valid-feedback"></div>
                    </div>
                    <div class="col-12 col-md-12 mb-3">
                        <label for="nilai">Nilai Perolehan</label>
                        <input type="text" class="form-control" id="nilai" placeholder="Masukkan Nilai Perolehan"
                            required="">
                        <div class="invalid-feedback">Masukkan Niali Perolehan yang valid.</div>
                        <div class="valid-feedback"></div>
                    </div>
                    <div class="col-12 col-md-12 mb-3">
                        <label for="umur_ekonomis">Umur Ekonomis</label>
                        <input type="text" class="form-control" id="umur_ekonomis" placeholder="Masukkan Umur Ekonomis"
                            required="">
                        <div class="invalid-feedback">Masukkan Umur Ekonomis yang valid.</div>
                        <div class="valid-feedback"></div>
                    </div>
                    <div class="col-12 col-md-12 mb-3">
                        <label for="tanggal_perolehan">Tanggal Perolehan</label>
                        <input type="date" class="form-control" id="tanggal_perolehan" placeholder="Pilih Tanggal Perolehan"
                            required="">
                        <div class="invalid-feedback">Masukkan Tanggal Perolehan yang valid.</div>
                        <div class="valid-feedback"></div>
                    </div>
                    <div class="col-12 col-md-12 mb-3">
                        <label for="kategori">Kategori Transaski</label>
                        <select class="form-control" id="kategori" required>
                            @foreach ($kategories as $kat)
                                <option value="{{ $kat->id }}">{{ $kat->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Masukkan Kategori Transaksi yang valid.</div>
                        <div class="valid-feedback"></div>
                    </div>
                    <div class="col-12 col-md-12 mb-3">
                        <label for="akun_kredit">Pilih Akun Kredit</label>
                        <select class="form-control" id="akun_kredit" required>
                            <option value="101">Kas</option>
                            <option value="102">Bank</option>
                        </select>
                        <div class="invalid-feedback">Pilih Akun Kredit yang valid.</div>
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
                    <h5 class="modal-title" id="modal-large-title">Edit Data</h5>
                    <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="" id="id">
                    <div class="col-12 col-md-12 mb-3">
                        <label for="edit_nama">Nama Aset</label>
                        <input type="text" class="form-control" id="edit_nama" placeholder="Masukkan Nama Aset"
                            required="">
                        <div class="invalid-feedback">Masukkan Nama Aset yang valid.</div>
                        <div class="valid-feedback"></div>
                    </div>
                    <div class="col-12 col-md-12 mb-3">
                        <label for="edit_nilai">Nilai Perolehan</label>
                        <input type="text" class="form-control" id="edit_nilai" placeholder="Masukkan Nilai Perolehan"
                            required="">
                        <div class="invalid-feedback">Masukkan Niali Perolehan yang valid.</div>
                        <div class="valid-feedback"></div>
                    </div>
                    <div class="col-12 col-md-12 mb-3">
                        <label for="edit_umur_ekonomis">Umur Ekonomis</label>
                        <input type="text" class="form-control" id="edit_umur_ekonomis" placeholder="Masukkan Umur Ekonomis"
                            required="">
                        <div class="invalid-feedback">Masukkan Umur Ekonomis yang valid.</div>
                        <div class="valid-feedback"></div>
                    </div>
                    <div class="col-12 col-md-12 mb-3">
                        <label for="edit_tanggal_perolehan">Tanggal Perolehan</label>
                        <input type="date" class="form-control" id="edit_tanggal_perolehan" placeholder="Pilih Tanggal Perolehan"
                            required="">
                        <div class="invalid-feedback">Masukkan Tanggal Perolehan yang valid.</div>
                        <div class="valid-feedback"></div>
                    </div>
                    <div class="col-12 col-md-12 mb-3">
                        <label for="edit_kategori">Kategori Transaski</label>
                        <select class="form-control" id="edit_kategori" required>
                            @foreach ($kategories as $kat)
                                <option value="{{ $kat->id }}">{{ $kat->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Masukkan Kategori Transaksi yang valid.</div>
                        <div class="valid-feedback"></div>
                    </div>
                    {{-- <div class="col-12 col-md-12 mb-3">
                        <label for="edit_akun_kredit">Pilih Akun Kredit</label>
                        <select class="form-control" id="edit_akun_kredit" required>
                            <option value="101">Kas</option>
                            <option value="102">Bank</option>
                        </select>
                        <div class="invalid-feedback">Pilih Akun Kredit yang valid.</div>
                        <div class="valid-feedback"></div>
                    </div> --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light close-modal" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="update">Update</button>
                    <button type="button" class="btn btn-danger delete">Hapus</button>
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

            $.ajax({
                url: "/aset/store",
                type: "POST",
                data: {
                    nama: $("#nama").val(),
                    nilai: $("#nilai").val(),
                    umur_ekonomis: $("#umur_ekonomis").val(),
                    tanggal_perolehan: $("#tanggal_perolehan").val(),
                    kategori: $("#kategori").val(),
                    akun_kredit : $("#akun_kredit").val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
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

        $(document).on("click", ".row-data", function() {
            modal = $("#modal-large-edit"), button = $(this);
            let id = $(this).data('id');
            buttonDisabled(button);

            $.ajax({
                url: "/aset/edit",
                type: "GET",
                data: {
                    id: id
                },
                success: function(response) {
                    buttonEnabled(button);
                    if (response.status) {
                        $("#edit_nama").val(response.data.nama);
                        $("#edit_nilai").val(response.data.nilai_perolehan);
                        $("#edit_umur_ekonomis").val(response.data.umur_ekonomis);
                        $("#edit_tanggal_perolehan").val(response.data.tanggal_perolehan);
                        $("#edit_kategori").val(response.data.kategori_transaksi_id);
                        $("#id").val(response.data.id);
                        $(".delete").attr('data-id', response.data.id);
                        openModal(modal);
                    } else {
                        showToastr('warning', response.message);
                    }
                },
                error: function(response) {
                    buttonEnabled(button);
                    showToastr('warning', response.message);
                }
            });
        })

        $("#update").on("click", function(e) {
            e.preventDefault();
            modal = $("#modal-large-edit"), button = $(this);
            closeModal(modal);
            buttonDisabled(button);

            $.ajax({
                url: "/aset/update",
                type: "POST",
                data: {
                    nama: $("#edit_nama").val(),
                    nilai: $("#edit_nilai").val(),
                    umur_ekonomis: $("#edit_umur_ekonomis").val(),
                    tanggal_perolehan: $("#edit_tanggal_perolehan").val(),
                    kategori: $("#edit_kategori").val(),
                    id: $("#id").val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    buttonEnabled(button);
                    if (response.status) {
                        showToastr('success', "Data berhasil diperbaharui!");
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

        $(document).on("click", ".delete", function() {
            let id = $(this).data("id");
            $("#delete").attr("data-id", id);
            closeModal(modal);
            $("#modal-warning").modal('show');
        });

        $("#delete").on("click", function() {
            button = $(this);
            buttonDisabled(button);

            $.ajax({
                url: "/aset/delete",
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
    </script>
@endsection
