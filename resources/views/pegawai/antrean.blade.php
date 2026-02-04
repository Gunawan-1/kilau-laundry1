<div class="row">
    @foreach($transaksis as $item)
    <div class="col-md-4">
        <div class="card shadow mb-4 border-left-primary">
            <div class="card-body">
                <h6><b>Invoice: {{ $item->invoice }}</b></h6>
                <p>Pelanggan: {{ $item->pelanggan }}</p>
                <div class="badge badge-warning">{{ strtoupper($item->status) }}</div>
                <hr>
                <form action="{{ route('update.status', $item->id) }}" method="POST">
                    @csrf
                    <select name="status" class="form-control form-control-sm mb-2">
                        <option value="cuci">Mulai Cuci</option>
                        <option value="setrika">Mulai Setrika</option>
                        <option value="packing">Mulai Packing</option>
                        <option value="selesai">Selesai</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-block btn-sm">Update Progres</button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>