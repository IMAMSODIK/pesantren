@forelse($rows as $row)
<tr>
    <td>{{ $row['tanggal'] }}</td>
    <td>{{ $row['deskripsi'] }}</td>
    <td>{{ $row['akun'] }}</td>
    <td class="text-end">{{ $row['debit'] }}</td>
    <td class="text-end">{{ $row['kredit'] }}</td>
</tr>
@empty
<tr>
    <td colspan="5" class="text-center data-empty">
        Tidak ada transaksi di periode ini
    </td>
</tr>
@endforelse
