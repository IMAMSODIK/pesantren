@extends('layouts.template')

@section('content')
<div class="mdk-drawer-layout js-mdk-drawer-layout" data-push data-responsive-width="992px">
    <div class="mdk-drawer-layout__content page">

        <div class="container-fluid page__heading-container">
            <div class="page__heading">
                <div class="d-flex align-items-center">
                    <div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Laporan Neraca
                                    ({{ $bulan }}/{{ $tahun }})
                                </li>
                            </ol>
                        </nav>
                        <h3 class="m-0">Neraca</h3>
                    </div>
                    <div class="ml-auto d-flex gap-2">
                        <button class="btn btn-danger mr-2" id="btn_pdf">
                            <i class="fa fa-file-pdf"></i> Export PDF
                        </button>
                        <button class="btn btn-success" id="btn_csv">
                            <i class="fa fa-file-excel"></i> Export CSV
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid page__container">

            {{-- Filter Bulan & Tahun --}}
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="bulan">Bulan</label>
                    <select id="bulan" class="form-control">
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}"
                                {{ $bulan == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="tahun">Tahun</label>
                    <input type="number" id="tahun" class="form-control"
                           value="{{ $tahun }}" min="2000" max="{{ date('Y') + 1 }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary" id="btn_filter">Tampilkan</button>
                </div>
            </div>

            <div class="row">
                <!-- ===== ASET ===== -->
                <div class="col-md-6">
                    <h4>Aset</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Akun</th>
                                <th class="text-end">Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="2"><strong>Aset Lancar</strong></td>
                            </tr>
                            @forelse ($asetList as $row)
                                <tr>
                                    <td>{{ $row->nama }}</td>
                                    <td class="text-end">Rp. {{ number_format($row->saldo, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center">Tidak ada data</td>
                                </tr>
                            @endforelse
                            <tr class="table-secondary">
                                <td><strong>Total Aset Lancar</strong></td>
                                <td class="text-end"><strong>Rp. {{ number_format($totalAsetLancar, 0, ',', '.') }}</strong></td>
                            </tr>

                            <tr>
                                <td colspan="2"><strong>Aset Tetap</strong></td>
                            </tr>
                            @forelse ($asetTetapList as $row)
                                <tr>
                                    <td>{{ $row->nama }}</td>
                                    <td class="text-end">Rp. {{ number_format($row->nilai_buku, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center">Tidak ada data</td>
                                </tr>
                            @endforelse
                            <tr class="table-secondary">
                                <td><strong>Total Aset Tetap</strong></td>
                                <td class="text-end"><strong>Rp. {{ number_format($totalAsetTetap, 0, ',', '.') }}</strong></td>
                            </tr>

                            <tr class="table-primary">
                                <td><strong>Total Aset</strong></td>
                                <td class="text-end"><strong>Rp. {{ number_format($totalAset, 0, ',', '.') }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- ===== LIABILITAS + EKUITAS ===== -->
                <div class="col-md-6">
                    <h4>Liabilitas dan Ekuitas</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Akun</th>
                                <th class="text-end">Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="2"><strong>Liabilitas</strong></td>
                            </tr>
                            @forelse ($liabilitasList as $row)
                                <tr>
                                    <td>{{ $row->nama }}</td>
                                    <td class="text-end">Rp. {{ number_format($row->saldo, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center">Tidak ada data</td>
                                </tr>
                            @endforelse
                            <tr class="table-secondary">
                                <td><strong>Total Liabilitas</strong></td>
                                <td class="text-end"><strong>Rp. {{ number_format($totalLiabilitas, 0, ',', '.') }}</strong></td>
                            </tr>

                            <tr>
                                <td colspan="2"><strong>Ekuitas</strong></td>
                            </tr>
                            @forelse ($ekuitasList as $row)
                                <tr>
                                    <td>{{ $row->nama }}</td>
                                    <td class="text-end">Rp. {{ number_format($row->saldo, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center">Tidak ada data</td>
                                </tr>
                            @endforelse
                            <tr class="table-secondary">
                                <td><strong>Total Ekuitas</strong></td>
                                <td class="text-end"><strong>Rp. {{ number_format($totalEkuitas, 0, ',', '.') }}</strong></td>
                            </tr>

                            <tr class="table-primary">
                                <td><strong>Total Liabilitas + Ekuitas</strong></td>
                                <td class="text-end"><strong>Rp. {{ number_format($totalPassiva, 0, ',', '.') }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
    @include('layouts.sidebar')
</div>
@endsection

@section('own-script')
<script>
    function getFilterParams() {
        let bulan = $('#bulan').val();
        let tahun = $('#tahun').val();
        if (!bulan || !tahun) {
            alert("Isi bulan dan tahun terlebih dahulu!");
            return false;
        }
        return `?bulan=${bulan}&tahun=${tahun}`;
    }

    $('#btn_filter').on('click', function() {
        let params = getFilterParams();
        if (params) {
            window.location.href = `/laporan/neraca${params}`;
        }
    });

    $('#btn_pdf').on('click', function(e) {
        e.preventDefault();
        let params = getFilterParams();
        if (params) {
            window.location.href = `/laporan/neraca/pdf${params}`;
        }
    });

    $('#btn_csv').on('click', function(e) {
        e.preventDefault();
        let params = getFilterParams();
        if (params) {
            window.location.href = `/laporan/neraca/csv${params}`;
        }
    });
</script>
@endsection
