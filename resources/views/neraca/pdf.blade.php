<!DOCTYPE html>
<html>
<head>
    <title>Neraca (Aset Neto)</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: right; }
        th { background-color: #f0f0f0; }
        td.left { text-align: left; }
        h2, h4 { text-align: center; margin: 0; padding: 0; }
    </style>
</head>
<body>
    <h2>Neraca (Aset Neto)</h2>
    <h4>Periode: {{ date('d-m-Y', strtotime($start_date)) }} s/d {{ date('d-m-Y', strtotime($end_date)) }}</h4>

    <h4>Aset</h4>
    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($aset as $item)
                <tr>
                    <td class="left">{{ $item['kode'] }}</td>
                    <td class="left">{{ $item['nama'] }}</td>
                    <td>{{ number_format($item['saldo'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h4>Liabilitas & Modal</h4>
    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($liabilitas as $item)
                <tr>
                    <td class="left">{{ $item['kode'] }}</td>
                    <td class="left">{{ $item['nama'] }}</td>
                    <td>{{ number_format($item['saldo'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h4>Aset Neto</h4>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($asetNeto as $item)
                <tr>
                    <td class="left">{{ $item['nama'] }}</td>
                    <td>{{ number_format($item['saldo'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <th class="left">Total Aset Neto</th>
                <th>{{ number_format($totalAsetNeto, 0, ',', '.') }}</th>
            </tr>
        </tbody>
    </table>
</body>
</html>
