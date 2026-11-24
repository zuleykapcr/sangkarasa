<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label small fw-semibold">Nama menu</label>
        <input type="text" name="nama_menu" value="{{ old('nama_menu', $menu->nama_menu ?? '') }}"
               class="form-control @error('nama_menu') is-invalid @enderror">
        @error('nama_menu') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label small fw-semibold">Kategori</label>
        <select name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror">
            <option value="">Pilih...</option>
            @foreach ($kategori as $k)
                <option value="{{ $k->id }}" {{ old('kategori_id', $menu->kategori_id ?? '') == $k->id ? 'selected' : '' }}>
                    {{ $k->nama_kategori }}
                </option>
            @endforeach
        </select>
        @error('kategori_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12">
        <label class="form-label small fw-semibold">Deskripsi</label>
        <textarea name="deskripsi" rows="3"
                  class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $menu->deskripsi ?? '') }}</textarea>
        @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label small fw-semibold">Harga per porsi (Rp)</label>
        <input type="number" name="harga" min="1000" step="500" value="{{ old('harga', isset($menu) ? (int) $menu->harga : '') }}"
               class="form-control @error('harga') is-invalid @enderror">
        @error('harga') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-8">
        <label class="form-label small fw-semibold">Gambar menu <span class="text-muted fw-normal">(jpg, png, webp, maks 2 MB)</span></label>
        <input type="file" name="gambar" accept="image/*" id="inputGambar"
               class="form-control @error('gambar') is-invalid @enderror">
        @error('gambar') <div class="invalid-feedback">{{ $message }}</div> @enderror

        <div class="mt-2">
            <img id="previewGambar" src="{{ isset($menu) ? $menu->gambar_url : '' }}" alt="Pratinjau"
                 class="preview-img {{ isset($menu) ? '' : 'd-none' }}">
        </div>
        @isset($menu)
            <div class="form-text">Kosongkan jika tidak ingin mengganti gambar.</div>
        @endisset
    </div>
</div>

<script>
    document.getElementById('inputGambar').addEventListener('change', function () {
        const file = this.files[0];
        const img = document.getElementById('previewGambar');
        if (file) {
            img.src = URL.createObjectURL(file);
            img.classList.remove('d-none');
        }
    });
</script>