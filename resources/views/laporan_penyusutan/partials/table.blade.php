{{-- Tabel Pendapatan --}}
<table class="table table-bordered table-sm mb-4">
    <thead>
        <tr class="fw-bold bg-dark">
            <th colspan="3" class="text-center text-white">Pendapatan</th>
        </tr>
        <tr>
            <th>Kode Akun</th>
            <th>Nama Akun</th>
            <th class="text-end">Nominal</th>
        </tr>
    </thead>
    <tbody>
        @forelse($pendapatanList as $row)
            <tr>
                <td>{{ $row['kode'] }}</td>
                <td>{{ $row['nama'] }}</td>
                <td class="text-end">Rp. {{ number_format($row['nominal'], 2, ',', '.') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center text-muted">Tidak ada pendapatan</td>
            </tr>
        @endforelse
        <tr class="fw-bold bg-light">
            <td colspan="2">Total Pendapatan</td>
            <td class="text-end">Rp. {{ number_format($totalPendapatan, 2, ',', '.') }}</td>
        </tr>
    </tbody>
</table>

{{-- Tabel Beban --}}
<table class="table table-bordered table-sm mb-4">
    <thead>
        <tr class="fw-bold bg-dark">
            <th colspan="3" class="text-center text-white">Beban</th>
        </tr>
        <tr>
            <th>Kode Akun</th>
            <th>Nama Akun</th>
            <th class="text-end">Nominal</th>
        </tr>
    </thead>
    <tbody>
        @forelse($bebanList as $row)
            <tr>
                <td>{{ $row['kode'] }}</td>
                <td>{{ $row['nama'] }}</td>
                <td class="text-end">Rp. {{ number_format($row['nominal'], 2, ',', '.') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center text-muted">Tidak ada beban</td>
            </tr>
        @endforelse
        <tr class="fw-bold bg-light">
            <td colspan="2">Total Beban</td>
            <td class="text-end">Rp. {{ number_format($totalBeban, 2, ',', '.') }}</td>
        </tr>
    </tbody>
</table>

{{-- Laba/Rugi Bersih --}}
<table class="table table-bordered table-sm">
    <tfoot class="fw-bold text-white bg-dark">
        <tr>
            <td colspan="2" class="text-center">Laba/Rugi Bersih</td>
            <td class="text-end">Rp. {{ number_format($labaBersih, 2, ',', '.') }}</td>
        </tr>
    </tfoot>
</table>
