<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Laba Rugi</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h2,
        h3 {
            text-align: center;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            padding: 6px;
            border-bottom: 1px solid #ccc;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .section-title {
            background: #f0f0f0;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h2>Laporan Laba Rugi</h2>
    @if ($tanggalAwal && $tanggalAkhir)
        <h3>Periode: {{ \Carbon\Carbon::parse($tanggalAwal)->format('d/m/Y') }} s/d
            {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d/m/Y') }}</h3>
    @elseif($bulan && $tahun)
        <h3>Periode: {{ \Carbon\Carbon::createFromFormat('m', $bulan)->translatedFormat('F') }} {{ $tahun }}</h3>
    @elseif($tahun)
        <h3>Periode: Tahun {{ $tahun }}</h3>
    @else
        <h3>Periode: -</h3>
    @endif

    {{-- Pendapatan Tidak Terikat --}}
    <table>
        <tr>
            <td colspan="2" class="section-title">Pendapatan Tidak Terikat</td>
        </tr>
        @foreach ($pendapatanTidakTerikat as $p)
            <tr>
                <td>{{ $p->kode }} - {{ $p->nama }}</td>
                <td class="right">{{ number_format($p->nominal, 0, ',', '.') }}</td>
            </tr>
        @endforeach
        <tr class="bold">
            <td>Total Pendapatan Tidak Terikat</td>
            <td class="right">{{ number_format($totalPendapatanTidakTerikat, 0, ',', '.') }}</td>
        </tr>
    </table>

    {{-- Pendapatan Terikat --}}
    <table>
        <tr>
            <td colspan="2" class="section-title">Pendapatan Terikat</td>
        </tr>
        @foreach ($pendapatanTerikat as $p)
            <tr>
                <td>{{ $p->kode }} - {{ $p->nama }}</td>
                <td class="right">{{ number_format($p->nominal, 0, ',', '.') }}</td>
            </tr>
        @endforeach
        <tr class="bold">
            <td>Total Pendapatan Terikat</td>
            <td class="right">{{ number_format($totalPendapatanTerikat, 0, ',', '.') }}</td>
        </tr>
    </table>

    {{-- Beban Tidak Terikat --}}
    <table>
        <tr>
            <td colspan="2" class="section-title">Beban Tidak Terikat</td>
        </tr>
        @foreach ($bebanTidakTerikat as $b)
            <tr>
                <td>{{ $b->kode }} - {{ $b->nama }}</td>
                <td class="right">{{ number_format($b->nominal, 0, ',', '.') }}</td>
            </tr>
        @endforeach
        <tr class="bold">
            <td>Total Beban Tidak Terikat</td>
            <td class="right">{{ number_format($totalBebanTidakTerikat, 0, ',', '.') }}</td>
        </tr>
    </table>

    {{-- Beban Terikat --}}
    <table>
        <tr>
            <td colspan="2" class="section-title">Beban Terikat</td>
        </tr>
        @foreach ($bebanTerikat as $b)
            <tr>
                <td>{{ $b->kode }} - {{ $b->nama }}</td>
                <td class="right">{{ number_format($b->nominal, 0, ',', '.') }}</td>
            </tr>
        @endforeach
        <tr class="bold">
            <td>Total Beban Terikat</td>
            <td class="right">{{ number_format($totalBebanTerikat, 0, ',', '.') }}</td>
        </tr>
    </table>

    {{-- Surplus / Defisit --}}
    <table>
        <tr>
            <td colspan="2" class="section-title">Surplus / (Defisit)</td>
        </tr>
        <tr>
            <td>Surplus / (Defisit) Tidak Terikat</td>
            <td class="right">{{ number_format($surplusTidakTerikat, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Surplus / (Defisit) Terikat</td>
            <td class="right">{{ number_format($surplusTerikat, 0, ',', '.') }}</td>
        </tr>
        <tr class="bold">
            <td>Total Surplus / (Defisit)</td>
            <td class="right">{{ number_format($surplusTotal, 0, ',', '.') }}</td>
        </tr>
    </table>
</body>

</html>
