<div class="table-responsive">
    <table class="table table-bordered table-sm w-100">
        <thead>
            <tr>
                <th>Aktivitas</th>
                <th class="text-end">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Arus Kas dari Aktivitas Operasi</strong></td>
                <td class="text-end">{{ number_format($kasDariOperasi,0,',','.') }}</td>
            </tr>
            <tr>
                <td><strong>Arus Kas dari Aktivitas Investasi</strong></td>
                <td class="text-end">{{ number_format($kasDariInvestasi,0,',','.') }}</td>
            </tr>
            <tr>
                <td><strong>Arus Kas dari Aktivitas Pendanaan</strong></td>
                <td class="text-end">{{ number_format($kasDariPendanaan,0,',','.') }}</td>
            </tr>
            <tr class="table-primary">
                <td><strong>Total Kas Bersih</strong></td>
                <td class="text-end"><strong>{{ number_format($totalKasBersih,0,',','.') }}</strong></td>
            </tr>
        </tbody>
    </table>
</div>
