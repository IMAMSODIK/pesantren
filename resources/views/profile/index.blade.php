@extends('layouts.template')

@section('content')
    <div class="mdk-drawer-layout js-mdk-drawer-layout" data-push data-responsive-width="992px">
        <div class="mdk-drawer-layout__content page">

            <div style="padding-bottom: calc(5.125rem / 2); position: relative; margin-bottom: 1.5rem;">
                <div style="min-height: 150px; background-image: url('https://static.vecteezy.com/system/resources/previews/015/235/458/non_2x/financial-management-concept-and-investment-banner-template-of-payment-with-money-free-vector.jpg')">
                    <div class="d-flex align-items-end container-fluid page__container"
                        style="position: absolute; left: 0; right: 0; bottom: 0;">
                        <div class="avatar avatar-xl">
                            <img src="{{asset('own_assets/images/default_user.png')}}" alt="avatar" class="avatar-img rounded"
                                style="border: 2px solid white;">
                        </div>
                        <div class="card-header card-header-tabs-basic nav flex" role="tablist">
                            <a href="#activity" class="active show" data-toggle="tab" role="tab"
                                aria-selected="true">Profile</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid page__container">
                <div class="row">
                    <div class="col-lg-3">
                        <h1 class="h4 mb-1">{{$users->name}} <small><span class="text-white badge bg-secondary">{{$users->role}}</span></small></h1>
                        <p class="text-muted">{{$users->email}}</p>
                    </div>
                    <div class="col-lg-9">
                        <div class="tab-content">
                            <div class="tab-pane active" id="activity">

                                <div class="card">
                                    <div class="px-4 py-3">
                                        <div class="d-flex mb-1">
                                            <div class="flex">
                                                <div class="d-flex align-items-center mb-1">
                                                    <strong class="text-15pt">Update Profile</strong>
                                                </div>
                                                <div>
                                                    <div class="row">
                                                        <div class="col-12 col-md-12 mb-3">
                                                            <label for="nama">Nama</label>
                                                            <input type="text" class="form-control" id="nama" placeholder="Masukkan Nama"
                                                                required="">
                                                            <div class="invalid-feedback">Masukkan Nama yang valid.</div>
                                                            <div class="valid-feedback"></div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-12 col-md-12 mb-3">
                                                            <label for="email">Email</label>
                                                            <input type="text" class="form-control" id="email" placeholder="Masukkan Email"
                                                                required="">
                                                            <div class="invalid-feedback">Masukkan Email yang valid.</div>
                                                            <div class="valid-feedback"></div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="ml-auto mr-3 mb-3">
                                                            <button class="btn btn-success" id="update_profile">Update</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="px-4 py-3">
                                        <div class="d-flex mb-1">
                                            <div class="flex">
                                                <div class="d-flex align-items-center mb-1">
                                                    <strong class="text-15pt">Ubah Password</strong>
                                                </div>
                                                <div>
                                                    <div class="row">
                                                        <div class="col-12 col-md-12 mb-3">
                                                            <label for="password_lama">Password Lama</label>
                                                            <input type="text" class="form-control" id="password_lama" placeholder="Masukkan Password Lama"
                                                                required="">
                                                            <div class="invalid-feedback">Masukkan Password Lama yang valid.</div>
                                                            <div class="valid-feedback"></div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-12 col-md-12 mb-3">
                                                            <label for="password_baru">Password Baru</label>
                                                            <input type="text" class="form-control" id="password_baru" placeholder="Masukkan Password Lama"
                                                                required="">
                                                            <div class="invalid-feedback">Masukkan Password Lama yang valid.</div>
                                                            <div class="valid-feedback"></div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-12 col-md-12 mb-3">
                                                            <label for="confirm_password">Konfirmasi Password</label>
                                                            <input type="text" class="form-control" id="confirm_password" placeholder="Masukkan Konfirmasi Password"
                                                                required="">
                                                            <div class="invalid-feedback">Masukkan Konfirmasi Password yang valid.</div>
                                                            <div class="valid-feedback"></div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="ml-auto mr-3 mb-3">
                                                            <button class="btn btn-success" id="update_password">Update</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
        let button = "";
        $("#update_profile").on("click", function(e) {
            e.preventDefault();
            button = $(this);
            buttonDisabled(button);

            $.ajax({
                url: "/profile/update-data",
                type: "POST",
                data: {
                    name: $("#nama").val(),
                    email: $("#email").val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    buttonEnabled(button);
                    if (response.status) {
                        showToastr('success', "Data berhasil diubah!");
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

        $("#update_password").on("click", function(e) {
            e.preventDefault();
            button = $(this);
            buttonDisabled(button);

            $.ajax({
                url: "/profile/update-password",
                type: "POST",
                data: {
                    password_lama: $("#password_lama").val(),
                    password_baru: $("#password_baru").val(),
                    confirm_password: $("#confirm_password").val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    buttonEnabled(button);
                    if (response.status) {
                        showToastr('success', "Data berhasil diubah!");
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
    </script>
@endsection
