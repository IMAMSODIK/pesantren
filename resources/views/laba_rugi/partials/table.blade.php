{{-- Pendapatan Tidak Terikat --}}
<table class="table table-bordered table-sm mb-4">
    <thead>
        <tr class="fw-bold bg-dark">
            <th colspan="3" class="text-center text-white">Pendapatan Tidak Terikat</th>
        </tr>
        <tr>
            <th>Kode Akun</th>
            <th>Nama Akun</th>
            <th class="text-end">Nominal</th>
        </tr>
    </thead>
    <tbody>
        @forelse($pendapatanTidakTerikat as $row)
            <tr>
                <td>{{ $row['kode'] }}</td>
                <td>{{ $row['nama'] }}</td>
                <td class="text-end">Rp {{ number_format($row['nominal'], 2, ',', '.') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center text-muted">Tidak ada pendapatan</td>
            </tr>
        @endforelse
        <tr class="fw-bold bg-light">
            <td colspan="2">Total Pendapatan Tidak Terikat</td>
            <td class="text-end">Rp {{ number_format($totalPendapatanTidakTerikat, 2, ',', '.') }}</td>
        </tr>
    </tbody>
</table>

{{-- Beban Tidak Terikat --}}
<table class="table table-bordered table-sm mb-4">
    <thead>
        <tr class="fw-bold bg-dark">
            <th colspan="3" class="text-center text-white">Beban Tidak Terikat</th>
        </tr>
        <tr>
            <th>Kode Akun</th>
            <th>Nama Akun</th>
            <th class="text-end">Nominal</th>
        </tr>
    </thead>
    <tbody>
        @forelse($bebanTidakTerikat as $row)
            <tr>
                <td>{{ $row['kode'] }}</td>
                <td>{{ $row['nama'] }}</td>
                <td class="text-end">Rp {{ number_format($row['nominal'], 2, ',', '.') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center text-muted">Tidak ada beban</td>
            </tr>
        @endforelse
        <tr class="fw-bold bg-light">
            <td colspan="2">Total Beban Tidak Terikat</td>
            <td class="text-end">Rp {{ number_format($totalBebanTidakTerikat, 2, ',', '.') }}</td>
        </tr>
        <tr class="fw-bold bg-secondary text-white">
            <td colspan="2">Surplus / (Defisit) Tidak Terikat</td>
            <td class="text-end">Rp {{ number_format($surplusTidakTerikat, 2, ',', '.') }}</td>
        </tr>
    </tbody>
</table>

{{-- Pendapatan Terikat --}}
<table class="table table-bordered table-sm mb-4">
    <thead>
        <tr class="fw-bold bg-dark">
            <th colspan="3" class="text-center text-white">Pendapatan Terikat</th>
        </tr>
        <tr>
            <th>Kode Akun</th>
            <th>Nama Akun</th>
            <th class="text-end">Nominal</th>
        </tr>
    </thead>
    <tbody>
        @forelse($pendapatanTerikat as $row)
            <tr>
                <td>{{ $row['kode'] }}</td>
                <td>{{ $row['nama'] }}</td>
                <td class="text-end">Rp {{ number_format($row['nominal'], 2, ',', '.') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center text-muted">Tidak ada pendapatan</td>
            </tr>
        @endforelse
        <tr class="fw-bold bg-light">
            <td colspan="2">Total Pendapatan Terikat</td>
            <td class="text-end">Rp {{ number_format($totalPendapatanTerikat, 2, ',', '.') }}</td>
        </tr>
    </tbody>
</table>

{{-- Beban Terikat --}}
<table class="table table-bordered table-sm mb-4">
    <thead>
        <tr class="fw-bold bg-dark">
            <th colspan="3" class="text-center text-white">Beban Terikat</th>
        </tr>
        <tr>
            <th>Kode Akun</th>
            <th>Nama Akun</th>
            <th class="text-end">Nominal</th>
        </tr>
    </thead>
    <tbody>
        @forelse($bebanTerikat as $row)
            <tr>
                <td>{{ $row['kode'] }}</td>
                <td>{{ $row['nama'] }}</td>
                <td class="text-end">Rp {{ number_format($row['nominal'], 2, ',', '.') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center text-muted">Tidak ada beban</td>
            </tr>
        @endforelse
        <tr class="fw-bold bg-light">
            <td colspan="2">Total Beban Terikat</td>
            <td class="text-end">Rp {{ number_format($totalBebanTerikat, 2, ',', '.') }}</td>
        </tr>
        <tr class="fw-bold bg-secondary text-white">
            <td colspan="2">Surplus / (Defisit) Terikat</td>
            <td class="text-end">Rp {{ number_format($surplusTerikat, 2, ',', '.') }}</td>
        </tr>
    </tbody>
</table>

{{-- Total Surplus / Defisit --}}
<table class="table table-bordered table-sm">
    <tfoot class="fw-bold text-white bg-primary">
        <tr>
            <td colspan="2" class="text-center">Total Surplus / (Defisit)</td>
            <td class="text-end">Rp {{ number_format($surplusTotal, 2, ',', '.') }}</td>
        </tr>
    </tfoot>
</table>
