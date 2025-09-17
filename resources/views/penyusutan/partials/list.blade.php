@foreach ($transaksis as $transaksi)
    <div class="row align-items-center projects-item mb-1 transaksi" style="cursor: pointer"
        data-id="{{ $transaksi->id }}">
        <div class="col-sm-auto mb-1 mb-sm-0">
            <div class="text-dark-gray">{{ $transaksi->format_tanggal }}</div>
        </div>
        <div class="col-sm">
            <div class="card m-0">
                <div class="px-4 py-3">
                    <div class="row align-items-center">
                        <div class="col-md-6" style="min-width: 300px">
                            <div class="d-flex align-items-center">
                                <a class="text-body">
                                    <strong class="text-15pt mr-2">
                                        {{ \Illuminate\Support\Str::limit($transaksi->deskripsi, 50, '...') }}
                                    </strong>
                                </a>
                            </div>
                            <div class="d-flex align-items-center">
                                <small class="text-dark-gray mr-2">Dibuat Oleh : </small>
                                <a class="d-flex align-items-middle">
                                    <i class="material-icons icon-muted icon-20pt mr-2">account_circle</i>
                                    {{ $transaksi->createdBy->name }}
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-center">
                            <span class="badge badge-success">{{ $transaksi->kategoriTransaksi->name }}</span>
                        </div>
                        <div class="col-md-3 text-md-right" style="min-width: 140px;">
                            <a class="text-body">{{ $transaksi->format_rupiah }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach

@if($transaksis->isEmpty())
    <p class="text-center text-muted">Tidak ada transaksi ditemukan</p>
@endif
