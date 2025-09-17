@forelse($bukuBesar as $akun => $data)
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <strong>{{ $akun }}</strong>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" width="120">Tanggal</th>
                        <th class="text-center">Deskripsi</th>
                        <th class="text-center">Debit</th>
                        <th class="text-center">Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['rows'] as $row)
                        <tr>
                            <td class="text-center">{{ $row['tanggal'] }}</td>
                            <td>{{ $row['deskripsi'] }}</td>
                            <td class="text-end">{{ $row['debit'] }}</td>
                            <td class="text-end">{{ $row['kredit'] }}</td>
                        </tr>
                    @endforeach
                    <tr class="fw-bold bg-light">
                        <td colspan="2" class="text-center">TOTAL</td>
                        <td class="text-end">
                            Rp. {{ number_format($data['totalDebit'], 2, ',', '.') }}
                        </td>
                        <td class="text-end">
                            Rp. {{ number_format($data['totalKredit'], 2, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@empty
    <div class="alert alert-warning">Tidak ada transaksi pada periode ini.</div>
@endforelse
