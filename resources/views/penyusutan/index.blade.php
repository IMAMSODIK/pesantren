@extends('layouts.template')

@section('content')
    <div class="mdk-drawer-layout js-mdk-drawer-layout" data-push data-responsive-width="992px">
        <div class="mdk-drawer-layout__content page">

            <div class="container-fluid  page__heading-container">
                <div class="page__heading">

                    <div class="d-flex align-items-center">
                        <div>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Penyusutan</li>
                                </ol>
                            </nav>
                            <h3 class="m-0">Penyusutan Aset</h3>
                            <small>Preview & posting jurnal penyusutan per periode</small>
                            <div class="me-2">
                                <label for="periode" class="form-label mb-1">Periode</label>
                                <input type="month" id="periode" class="form-control" value="{{ $periode }}">
                            </div>
                        </div>
                        <div class="ml-auto">
                            <button id="btnReload" class="btn btn-light">Tampilkan</button>
                            <button id="btnPostAll" class="btn btn-primary">Proses Semua Belum</button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="container-fluid page__container">

                {{-- <div class="card card-form d-flex flex-column flex-sm-row">
                    <div class="card-form__body card-body-form-group flex">
                        <div class="row">
                            <div class="col-lg-3 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label for="filter_name">Cari Data</label>
                                    <input id="filter_name" type="text" class="form-control"
                                        placeholder="Masukkan keyword">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label for="filter_buckets">Kategori Data</label><br>
                                    <select id="filter_buckets" class="custom-select">
                                        <option value="all">Semua</option>
                                        @foreach ($all_kategories as $kat)
                                            <option value="{{ $kat->id }}">{{ $kat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_mulai">Tanggal Mulai</label><br>
                                    <input type="date" class="custom-select" id="tanggal_mulai">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_akhir">Tanggal Akrhir</label><br>
                                    <input type="date" class="custom-select" id="tanggal_akhir">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="ml-auto mr-3 mb-3">
                                <button class="btn btn-success" id="btn_filter">
                                    <i class="fa fa-filter me-2" aria-hidden="true"></i> Filter
                                </button>
                            </div>
                        </div>
                    </div>
                    <button
                        class="btn bg-white border-left border-top border-top-sm-0 rounded-top-0 rounded-top-sm rounded-left-sm-0" onclick="location.reload()">
                        <i class="material-icons text-primary">refresh</i>
                    </button>
                </div> --}}

                <div id="transaksi_list">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered align-middle" id="tblPenyusutan">
                            <thead>
                                <tr>
                                    <th style="width:40px"><input type="checkbox" id="checkAll"></th>
                                    <th>Aset</th>
                                    <th>Nilai Perolehan</th>
                                    <th>Umur (bln)</th>
                                    <th>Beban Bulan Ini</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rows as $r)
                                    <tr data-id="{{ $r['id'] }}">
                                        <td>
                                            @if ($r['selectable'])
                                                <input type="checkbox" class="row-check">
                                            @endif
                                        </td>
                                        <td>{{ $r['nama'] }}</td>
                                        <td class="text-end">{{ $r['nilai_perolehan'] }}</td>
                                        <td class="text-center">{{ $r['umur'] }}</td>
                                        <td class="text-end">{{ $r['beban'] }}</td>
                                        <td>
                                            <span
                                                class="badge {{ $r['status'] == 'Belum diposting' ? 'bg-warning' : 'bg-success' }}">
                                                {{ $r['status'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-end">Total Belum Diposting</th>
                                    <th class="text-end">{{ number_format($total, 2, '.', ',') }}</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="mt-3 d-flex gap-2">
                    <button id="btnPostSelected" class="btn btn-info">Proses Yang Dipilih</button>
                </div>

            </div>

        </div>
        <!-- // END drawer-layout__content -->

        @include('layouts.sidebar')
    </div>
@endsection

@section('own-script')
    <script>
document.getElementById('btnReload').addEventListener('click', function(){
  const p = document.getElementById('periode').value;
  const url = new URL(window.location.href);
  url.searchParams.set('periode', p);
  window.location.href = url.toString();
});

document.getElementById('checkAll').addEventListener('change', function(e){
  document.querySelectorAll('.row-check').forEach(cb => cb.checked = e.target.checked);
});

function collectSelectedIds() {
  let ids = [];
  document.querySelectorAll('#tblPenyusutan tbody tr').forEach(tr => {
    const cb = tr.querySelector('.row-check');
    if (cb && cb.checked) ids.push(tr.getAttribute('data-id'));
  });
  return ids;
}

function postDepreciation(asetIds = []) {
  const btnAll = document.getElementById('btnPostAll');
  const btnSel = document.getElementById('btnPostSelected');
  btnAll.disabled = true; btnSel.disabled = true;

  const payload = {
    _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    periode: document.getElementById('periode').value,
  };
  if (asetIds.length) payload.aset_ids = asetIds;

  fetch("{{ route('penyusutan.proses') }}", {
    method: 'POST',
    headers: {'Accept': 'application/json'},
    body: toFormData(payload),
  })
  .then(r => r.json())
  .then(res => {
    btnAll.disabled = false; btnSel.disabled = false;
    if (res.status) {
      showToastr('success', res.message);
      // refresh halaman agar status & total update
      setTimeout(()=>{ document.getElementById('btnReload').click(); }, 800);
    } else {
      showToastr('warning', res.message || 'Gagal memproses');
    }
  })
  .catch(err => {
    btnAll.disabled = false; btnSel.disabled = false;
    showToastr('warning', 'Terjadi kesalahan jaringan.');
  });
}

function toFormData(obj){
  const fd = new FormData();
  Object.entries(obj).forEach(([k,v])=>{
    if (Array.isArray(v)) v.forEach(val => fd.append(k+'[]', val));
    else fd.append(k, v);
  });
  return fd;
}

document.getElementById('btnPostAll').addEventListener('click', function(){
  postDepreciation([]); // server akan proses semua yang belum
});

document.getElementById('btnPostSelected').addEventListener('click', function(){
  const ids = collectSelectedIds();
  if (!ids.length) { showToastr('warning','Pilih minimal satu aset.'); return; }
  postDepreciation(ids);
});
</script>
@endsection
