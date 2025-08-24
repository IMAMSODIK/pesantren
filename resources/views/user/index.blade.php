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
                                    <li class="breadcrumb-item active" aria-current="page">Pengguna</li>
                                </ol>
                            </nav>
                            <h1 class="m-0">Pengguna</h1>
                        </div>
                        <div class="ml-auto">
                            <button class="btn btn-info" data-toggle="modal" data-target="#modal-large" type="button"
                                id="add-data">
                                + Tambah Pengguna
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
                                                    <th>ID</th>
                                                    <th>Nama</th>
                                                    <th>Email</th>
                                                    <th>Role</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $no = 1;
                                                @endphp
                                                @foreach ($users as $user)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ $user->name }}</td>
                                                        <td>{{ $user->email }}</td>
                                                        <td class="text-center">
                                                            @if ($user->role == 'admin')
                                                                <span class="text-white badge bg-primary">Admin</span>
                                                            @else
                                                                <span class="text-white badge bg-success">Operator</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            @if ($user->status == 'active')
                                                                <span class="text-white badge bg-success">Aktif</span>
                                                            @else
                                                                <span class="text-white badge bg-danger">Tidak Aktif</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="d-flex flex-wrap gap-2">
                                                                <button class="btn btn-sm btn-warning text-white edit"
                                                                    style="margin-right: 5px" data-id="{{ $user->id }}">
                                                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                                                </button>
                                                                <button class="btn btn-sm btn-danger text-white delete"
                                                                    data-toggle="modal" data-target="#modal-warning"
                                                                    data-id="{{ $user->id }}">
                                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                                </button>
                                                            </div>
                                                        </td>
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
                        <label for="validationSample03">Nama</label>
                        <input type="text" class="form-control" id="validationSample03" placeholder="Masukkan nama"
                            required="">
                        <div class="invalid-feedback">Masukkan nama yang valid.</div>
                        <div class="valid-feedback"></div>
                    </div>

                    <div class="col-12 col-md-12 mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="user@example.com"
                            required="">
                        <div class="invalid-feedback">Masukkan email yang valid.</div>
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
                        <label for="edit_nama">Nama</label>
                        <input type="text" class="form-control" id="edit_nama" placeholder="Masukkan nama"
                            required="">
                        <div class="invalid-feedback">Masukkan nama yang valid.</div>
                        <div class="valid-feedback"></div>
                    </div>

                    <div class="col-12 col-md-12 mb-3">
                        <label for="edit_email">Email</label>
                        <input type="email" class="form-control" id="edit_email" placeholder="user@example.com"
                            required="">
                        <div class="invalid-feedback">Masukkan email yang valid.</div>
                        <div class="valid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light close-modal" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="update">Update</button>
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
                url: "/user/store",
                type: "POST",
                data: {
                    name: $("#validationSample03").val(),
                    email: $("#email").val(),
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

        $(document).on("click", ".edit", function() {
            modal = $("#modal-large-edit"), button = $(this);
            buttonDisabled(button);

            $.ajax({
                url: "/user/edit",
                type: "GET",
                data: {
                    id: $(this).data('id')
                },
                success: function(response) {
                    buttonEnabled(button);
                    if (response.status) {
                        $("#edit_nama").val(response.data.name);
                        $("#edit_email").val(response.data.email);
                        $("#id").val(response.data.id);
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
                url: "/user/update",
                type: "POST",
                data: {
                    name: $("#edit_nama").val(),
                    email: $("#edit_email").val(),
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
        });

        $("#delete").on("click", function() {
            button = $(this);
            buttonDisabled(button);

            $.ajax({
                url: "/user/delete",
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
