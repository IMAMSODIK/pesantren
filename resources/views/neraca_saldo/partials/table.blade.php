<table class="table table-bordered table-sm">
    <thead class="table table-bordered table-sm">
        <tr>
            <th class="text-center">Kode Akun</th>
            <th class="text-center">Nama Akun</th>
            <th class="text-center">Debit</th>
            <th class="text-center">Kredit</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($data as $row)
            <tr>
                <td class="text-center">{{ $row['kode'] }}</td>
                <td>{{ $row['nama'] }}</td>
                <td class="text-end">
                    {{ $row['debit'] > 0 ? 'Rp. ' . number_format($row['debit'], 2, ',', '.') : '-' }}
                </td>
                <td class="text-end">
                    {{ $row['kredit'] > 0 ? 'Rp. ' . number_format($row['kredit'], 2, ',', '.') : '-' }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center text-muted">Tidak ada saldo pada periode ini.</td>
            </tr>
        @endforelse
    </tbody>
    @if(count($data) > 0)
        <tfoot class="fw-bold bg-light">
            <tr>
                <td colspan="2" class="text-center">TOTAL</td>
                <td class="text-end">
                    Rp. {{ number_format(collect($data)->sum('debit'), 2, ',', '.') }}
                </td>
                <td class="text-end">
                    Rp. {{ number_format(collect($data)->sum('kredit'), 2, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    @endif
</table>
