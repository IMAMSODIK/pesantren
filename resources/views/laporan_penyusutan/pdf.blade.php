<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $pageTitle }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 5px; }
        th { background-color: #f0f0f0; text-align: left; }
        .text-end { text-align: right; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">{{ $pageTitle }}</h2>
    <p style="text-align: center;">Periode: {{ $bulan }}/{{ $tahun }}</p>

    <table>
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
                <td class="text-end">Rp. {{ number_format($aset->nilai_perolehan, 0, ',', '.') }}</td>
                <td class="text-end">{{ $aset->umur_ekonomis }}</td>
                <td class="text-end">Rp. {{ number_format($aset->akumulasi_penyusutan, 0, ',', '.') }}</td>
                <td class="text-end">Rp. {{ number_format($aset->nilai_buku, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
