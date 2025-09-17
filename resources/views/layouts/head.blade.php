<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>{{ $pageTitle }}</title>

<link type="text/css" href="{{ asset('assets/vendor/perfect-scrollbar.css') }}" rel="stylesheet">

<link type="text/css" href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/app.rtl.css') }}" rel="stylesheet">

<link type="text/css" href="{{ asset('assets/css/vendor-material-icons.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/vendor-material-icons.rtl.css') }}" rel="stylesheet">

<link type="text/css" href="{{ asset('assets/css/vendor-fontawesome-free.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/vendor-fontawesome-free.rtl.css') }}" rel="stylesheet">

<link type="text/css" href="{{ asset('assets/css/vendor-flatpickr.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/vendor-flatpickr.rtl.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/vendor-flatpickr-airbnb.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/vendor-flatpickr-airbnb.rtl.css') }}" rel="stylesheet">

<link type="text/css" href="{{ asset('assets/vendor/jqvmap/jqvmap.min.css') }}" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/vendor/toastr.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">


<style>
    .container {
        width: 100% !important;
        max-width: 100% !important;
    }

    td,
    th {
        font-size: 18px !important;
    }

    td .badge {
        font-size: 16px !important;
    }

    td button {
        font-size: 16px !important;
    }

    .modal-body {
        max-height: 70vh;
        /* tinggi maksimal sesuai layar */
        overflow-y: auto;
        /* aktifkan scroll vertikal */
    }

    .text-end {
        text-align: right;
    }
</style>
