<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $pageTitle }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #000; padding: 5px; }
        th { background-color: #f0f0f0; text-align: left; }
        .text-end { text-align: right; }
        .table-title { background-color: #d9d9d9; font-weight: bold; }
        .total { font-weight: bold; background-color: #f2f2f2; }
    </style>
</head>
<body>

    <h2 style="text-align: center;">{{ $pageTitle }}</h2>
    <p style="text-align: center;">Periode: {{ $bulan }}/{{ $tahun }}</p>

    <!-- ===== ASET ===== -->
    <h4>Aset</h4>
    <table>
        <thead>
            <tr>
                <th>Akun</th>
                <th class="text-end">Saldo</th>
            </tr>
        </thead>
        <tbody>
            <tr><td colspan="2" class="table-title">Aset Lancar</td></tr>
            @foreach ($asetList as $row)
            <tr>
                <td>{{ $row->nama }}</td>
                <td class="text-end">Rp. {{ number_format($row->saldo, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total">
                <td>Total Aset Lancar</td>
                <td class="text-end">Rp. {{ number_format($totalAsetLancar, 0, ',', '.') }}</td>
            </tr>

            <tr><td colspan="2" class="table-title">Aset Tetap</td></tr>
            @foreach ($asetTetapList as $row)
            <tr>
                <td>{{ $row->nama }}</td>
                <td class="text-end">Rp. {{ number_format($row->nilai_buku, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total">
                <td>Total Aset Tetap</td>
                <td class="text-end">Rp. {{ number_format($totalAsetTetap, 0, ',', '.') }}</td>
            </tr>

            <tr class="total">
                <td>Total Aset</td>
                <td class="text-end">Rp. {{ number_format($totalAset, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- ===== LIABILITAS DAN EKUITAS ===== -->
    <h4>Liabilitas dan Ekuitas</h4>
    <table>
        <thead>
            <tr>
                <th>Akun</th>
                <th class="text-end">Saldo</th>
            </tr>
        </thead>
        <tbody>
            <tr><td colspan="2" class="table-title">Liabilitas</td></tr>
            @foreach ($liabilitasList as $row)
            <tr>
                <td>{{ $row->nama }}</td>
                <td class="text-end">Rp. {{ number_format($row->saldo, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total">
                <td>Total Liabilitas</td>
                <td class="text-end">Rp. {{ number_format($totalLiabilitas, 0, ',', '.') }}</td>
            </tr>

            <tr><td colspan="2" class="table-title">Ekuitas</td></tr>
            @foreach ($ekuitasList as $row)
            <tr>
                <td>{{ $row->nama }}</td>
                <td class="text-end">Rp. {{ number_format($row->saldo, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total">
                <td>Total Ekuitas</td>
                <td class="text-end">Rp. {{ number_format($totalEkuitas, 0, ',', '.') }}</td>
            </tr>

            <tr class="total">
                <td>Total Liabilitas + Ekuitas</td>
                <td class="text-end">Rp. {{ number_format($totalPassiva, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

</body>
</html>
