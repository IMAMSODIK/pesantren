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
                                    <li class="breadcrumb-item active" aria-current="page">Laporan Penyusutan Aset
                                        {{ $bulan }}/{{ $tahun }}</li>
                                </ol>
                            </nav>
                            <h3 class="m-0">Penyusutan Aset</h3>
                        </div>
                        <div class="ml-auto">
                            <button class="btn btn-danger" id="btn_pdf">
                                <i class="fa fa-file-pdf"></i> Export PDF
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="container-fluid page__container">

                <div class="row">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Aset</th>
                                <th>Nilai Perolehan</th>
                                <th>Umur Ekonomis (Bulan)</th>
                                <th>Akumulasi Penyusutan</th>
                                <th>Nilai Buku</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($asetList as $aset)
                                <tr>
                                    <td>{{ $aset->nama }}</td>
                                    <td class="text-end">{{ number_format($aset->nilai_perolehan, 0, ',', '.') }}</td>
                                    <td class="text-end">{{ $aset->umur_ekonomis }}</td>
                                    <td class="text-end">{{ number_format($aset->akumulasi_penyusutan, 0, ',', '.') }}</td>
                                    <td class="text-end">{{ number_format($aset->nilai_buku, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

        </div>

        @include('layouts.sidebar')
    </div>
@endsection

@section('own-script')
    <script>
        $('#btn_pdf').on('click', function(e) {
            e.preventDefault();

            window.location.href = `/laporan/penyusutan-aset/pdf`;
        });
    </script>
@endsection
