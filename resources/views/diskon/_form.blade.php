<div class="form-group">
    <label for="nama_diskon">Nama Diskon</label>
    <input type="text" name="nama_diskon" class="form-control @error('nama_diskon') is-invalid @enderror" id="nama_diskon" value="{{ old('nama_diskon', $diskon->nama_diskon ?? '') }}" required>
    @error('nama_diskon')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="tipe">Tipe Diskon</label>
            <select name="tipe" id="tipe" class="form-control @error('tipe') is-invalid @enderror" required>
                <option value="persen" {{ old('tipe', $diskon->tipe ?? '') == 'persen' ? 'selected' : '' }}>Persen (%)</option>
                <option value="tetap" {{ old('tipe', $diskon->tipe ?? '') == 'tetap' ? 'selected' : '' }}>Tetap (Rp)</option>
            </select>
            @error('tipe')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="nilai">Nilai</label>
            <input type="number" name="nilai" class="form-control @error('nilai') is-invalid @enderror" id="nilai" value="{{ old('nilai', $diskon->nilai ?? '') }}" required>
            @error('nilai')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="form-group">
    <label for="status">Status</label>
    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
        <option value="1" {{ old('status', $diskon->status ?? '') == 1 ? 'selected' : '' }}>Aktif</option>
        <option value="0" {{ old('status', $diskon->status ?? '') == 0 ? 'selected' : '' }}>Tidak Aktif</option>
    </select>
    @error('status')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<hr>

<div class="form-group">
    <label for="jenis_aturan">Jenis Aturan Diskon</label>
    <select name="jenis_aturan" id="jenis_aturan" class="form-control @error('jenis_aturan') is-invalid @enderror" required>
        <option value="tanpa_aturan" {{ old('jenis_aturan', $diskon->jenis_aturan ?? '') == 'tanpa_aturan' ? 'selected' : '' }}>Manual (Tanpa Aturan)</option>
        <option value="berdasarkan_layanan_berat" {{ old('jenis_aturan', $diskon->jenis_aturan ?? '') == 'berdasarkan_layanan_berat' ? 'selected' : '' }}>Otomatis (Berdasarkan Layanan & Berat)</option>
    </select>
    @error('jenis_aturan')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<!-- Bagian Aturan Otomatis (Awalnya disembunyikan) -->
<div id="aturan_otomatis" style="display: none;">
    <div class="form-group">
        <label for="layanan_id_aturan">Pilih Layanan</label>
        <select name="layanan_id_aturan" id="layanan_id_aturan" class="form-control @error('layanan_id_aturan') is-invalid @enderror">
            <option value="">-- Pilih Layanan --</option>
            @foreach($layanans as $layanan)
                <option value="{{ $layanan->id }}" {{ old('layanan_id_aturan', $diskon->layanan_id_aturan ?? '') == $layanan->id ? 'selected' : '' }}>
                    {{ $layanan->nama_layanan }}
                </option>
            @endforeach
        </select>
        @error('layanan_id_aturan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="minimal_berat_aturan">Minimal Berat (Kg)</label>
        <input type="number" step="0.1" name="minimal_berat_aturan" class="form-control @error('minimal_berat_aturan') is-invalid @enderror" id="minimal_berat_aturan" value="{{ old('minimal_berat_aturan', $diskon->minimal_berat_aturan ?? '') }}">
        @error('minimal_berat_aturan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const jenisAturanSelect = document.getElementById('jenis_aturan');
        const aturanOtomatisDiv = document.getElementById('aturan_otomatis');

        function toggleAturanFields() {
            if (jenisAturanSelect.value === 'berdasarkan_layanan_berat') {
                aturanOtomatisDiv.style.display = 'block';
            } else {
                aturanOtomatisDiv.style.display = 'none';
            }
        }

        // Panggil saat halaman dimuat
        toggleAturanFields();

        // Panggil saat pilihan berubah
        jenisAturanSelect.addEventListener('change', toggleAturanFields);
    });
</script>
@endpush
