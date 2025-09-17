<div class="row">
    <div class="col-md-6">
        <h5>Daftar Piutang</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Akun</th>
                    <th class="text-end">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($piutang as $item)
                <tr>
                    <td>{{ $item->nama_akun }}</td>
                    <td class="text-end">Rp. {{ number_format($item->saldo,0,',','.') }}</td>
                </tr>
                @endforeach
                <tr>
                    <th>Total Piutang</th>
                    <th class="text-end">Rp. {{ number_format($totalPiutang,0,',','.') }}</th>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="col-md-6">
        <h5>Daftar Utang</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Akun</th>
                    <th class="text-end">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($utang as $item)
                <tr>
                    <td>{{ $item->nama_akun }}</td>
                    <td class="text-end">Rp. {{ number_format($item->saldo,0,',','.') }}</td>
                </tr>
                @endforeach
                <tr>
                    <th>Total Utang</th>
                    <th class="text-end">Rp. {{ number_format($totalUtang,0,',','.') }}</th>
                </tr>
            </tbody>
        </table>
    </div>
</div>
