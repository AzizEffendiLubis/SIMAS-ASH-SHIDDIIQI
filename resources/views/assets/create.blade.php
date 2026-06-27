@extends('layouts.app')
@section('title', 'Tambah Aset')
@section('page-title', 'Tambah Aset')

@section('content')
<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;">
    <div>
        <h1>Tambah Aset Baru</h1>
        <p>Isi formulir di bawah untuk menambahkan aset baru</p>
    </div>
    <a href="{{ route('assets.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('assets.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <p style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#2563eb;margin-bottom:16px;padding-bottom:8px;border-bottom:1.5px solid #eff6ff;">Informasi Barang</p>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Nama Barang <span style="color:#dc2626;">*</span></label>
                    <input type="text" name="nama_barang" class="form-control {{ $errors->has('nama_barang') ? 'is-invalid' : '' }}" placeholder="Contoh: Laptop ASUS VivoBook" value="{{ old('nama_barang') }}">
                    @error('nama_barang') <p class="invalid-feedback">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori <span style="color:#dc2626;">*</span></label>
                    <select name="kategori" class="form-control {{ $errors->has('kategori') ? 'is-invalid' : '' }}">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ old('kategori')==$cat?'selected':'' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                    @error('kategori') <p class="invalid-feedback">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Lokasi Barang <span style="color:#dc2626;">*</span></label>
                    <input type="text" name="lokasi_barang" class="form-control {{ $errors->has('lokasi_barang') ? 'is-invalid' : '' }}" placeholder="Contoh: Ruang Kelas 7A" value="{{ old('lokasi_barang') }}">
                    @error('lokasi_barang') <p class="invalid-feedback">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    {{-- unit_id: FK ke tabel units. Admin Utama pilih bebas; Admin Unit dikunci ke unitnya sendiri. --}}
                    <label class="form-label">Unit Kerja <span style="color:#dc2626;">*</span></label>
                    <select name="unit_id" class="form-control {{ $errors->has('unit_id') ? 'is-invalid' : '' }}"
                        {{ auth()->user()->isAdminUnit() ? 'disabled' : '' }}>
                        <option value="">-- Pilih Unit --</option>
                        @foreach($units as $unit)
                        <option value="{{ $unit->id }}" {{ old('unit_id', auth()->user()->isAdminUnit() ? auth()->user()->unit_id : '') == $unit->id ? 'selected' : '' }}>
                            {{ $unit->nama_unit }}
                        </option>
                        @endforeach
                    </select>
                    {{-- Jika Admin Unit, kirim unit_id via hidden agar tidak terblokir disabled --}}
                    @if(auth()->user()->isAdminUnit())
                    <input type="hidden" name="unit_id" value="{{ auth()->user()->unit_id }}">
                    @endif
                    @error('unit_id') <p class="invalid-feedback">{{ $message }}</p> @enderror
                </div>
            </div>

            <p style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#2563eb;margin-bottom:16px;margin-top:8px;padding-bottom:8px;border-bottom:1.5px solid #eff6ff;">Detail &amp; Kondisi</p>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Jumlah Barang <span style="color:#dc2626;">*</span></label>
                    <input type="number" name="jumlah_barang" class="form-control {{ $errors->has('jumlah_barang') ? 'is-invalid' : '' }}" min="1" value="{{ old('jumlah_barang', 1) }}">
                    @error('jumlah_barang') <p class="invalid-feedback">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    {{-- Satuan aset — FK ke units_satuan, diisi via seeder, bersifat tetap --}}
                    <label class="form-label">Satuan</label>
                    <select name="satuan_id" class="form-control {{ $errors->has('satuan_id') ? 'is-invalid' : '' }}">
                        <option value="">-- Pilih Satuan --</option>
                        @foreach($satuanList as $satuan)
                        <option value="{{ $satuan->id }}" {{ old('satuan_id')==$satuan->id?'selected':'' }}>{{ $satuan->nama_satuan }}</option>
                        @endforeach
                    </select>
                    @error('satuan_id') <p class="invalid-feedback">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    {{-- Sumber dana: FK ke funding_sources, dinamis dari DB --}}
                    <label class="form-label">Sumber Dana</label>
                    <select name="sumber_dana_id" class="form-control {{ $errors->has('sumber_dana_id') ? 'is-invalid' : '' }}">
                        <option value="">-- Pilih Sumber Dana --</option>
                        @foreach($fundingSources as $fs)
                        <option value="{{ $fs->id }}" {{ old('sumber_dana_id')==$fs->id?'selected':'' }}>{{ $fs->nama_sumber }}</option>
                        @endforeach
                    </select>
                    @error('sumber_dana_id') <p class="invalid-feedback">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Harga Barang (Rp) <span style="color:#dc2626;">*</span></label>
                    <input type="number" name="harga_barang" class="form-control {{ $errors->has('harga_barang') ? 'is-invalid' : '' }}" min="0" placeholder="0" value="{{ old('harga_barang') }}">
                    @error('harga_barang') <p class="invalid-feedback">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Pengadaan</label>
                    <input type="date" name="tanggal_pengadaan" class="form-control {{ $errors->has('tanggal_pengadaan') ? 'is-invalid' : '' }}" value="{{ old('tanggal_pengadaan') }}">
                    @error('tanggal_pengadaan') <p class="invalid-feedback">{{ $message }}</p> @enderror
                </div>
                <div class="form-group" id="foto-group">
                    <label class="form-label">Foto Barang</label>

                    {{-- Grid preview foto yang sudah dipilih --}}
                    <div id="foto-preview-grid" style="display:none; grid-template-columns:repeat(5,1fr); gap:10px; margin-bottom:10px;"></div>
                    <p id="foto-counter" style="display:none; font-size:12px; color:#6b7280; text-align:right; margin-bottom:8px;"></p>

                    {{-- Tombol tambah foto — disembunyikan otomatis saat sudah 5 --}}
                    <div id="foto-dropzone"
                        onclick="document.getElementById('foto-picker').click()"
                        style="border:1.5px dashed #d1d5db; border-radius:10px; padding:1.5rem; text-align:center;
                                cursor:pointer; background:#f9fafb; transition:border-color .15s,background .15s;"
                        onmouseover="this.style.borderColor='#2563eb';this.style.background='#eff6ff'"
                        onmouseout="this.style.borderColor='#d1d5db';this.style.background='#f9fafb'">
                        <i class="fas fa-cloud-upload-alt" style="font-size:24px; color:#9ca3af; display:block; margin-bottom:6px;"></i>
                        <p style="margin:0; font-size:13px; color:#6b7280;">Klik untuk pilih foto</p>
                        <span style="font-size:11px; color:#9ca3af;">JPG / PNG / WEBP · maks. 2 MB per foto · hingga 5 foto</span>
                    </div>

                    {{-- Hidden file input — single (bukan multiple) agar user pilih satu per satu --}}
                    <input type="file" id="foto-picker"
                        accept="image/jpg,image/jpeg,image/png,image/webp"
                        style="display:none">

                    {{-- Container hidden inputs name="fotos[]" yang akan dikirim ke controller --}}
                    <div id="foto-hidden-inputs"></div>

                    @error('fotos') <p class="invalid-feedback" style="display:block;">{{ $message }}</p> @enderror
                    @error('fotos.*') <p class="invalid-feedback" style="display:block;">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Spesifikasi</label>
                <input type="text" name="spesifikasi" class="form-control {{ $errors->has('spesifikasi') ? 'is-invalid' : '' }}" placeholder="Contoh: RAM 8GB, SSD 512GB" value="{{ old('spesifikasi') }}">
                @error('spesifikasi') <p class="invalid-feedback">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Dasar / Keterangan Persetujuan <span style="color:#dc2626;">*</span></label>
                <textarea name="keterangan_dasar" class="form-control {{ $errors->has('keterangan_dasar') ? 'is-invalid' : '' }}" rows="2" placeholder="Dasar penambahan atau keterangan persetujuan aset...">{{ old('keterangan_dasar') }}</textarea>
                @error('keterangan_dasar') <p class="invalid-feedback">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Keterangan Tambahan</label>
                <textarea name="keterangan" class="form-control" rows="3" placeholder="Keterangan tambahan tentang aset ini...">{{ old('keterangan') }}</textarea>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px;">
                <a href="{{ route('assets.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Aset</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const MAX      = 5;
    const photos   = [];   // { file, dataUrl }
    let primaryIdx = 0;

    const picker    = document.getElementById('foto-picker');
    const grid      = document.getElementById('foto-preview-grid');
    const counter   = document.getElementById('foto-counter');
    const dropzone  = document.getElementById('foto-dropzone');
    const hiddenWrap = document.getElementById('foto-hidden-inputs');

    picker.addEventListener('change', function (e) {
        const file = e.target.files[0];
        picker.value = '';
        if (!file) return;

        if (photos.length >= MAX) {
            alert('Maksimal 5 foto yang dapat diunggah.');
            return;
        }
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran foto "' + file.name + '" melebihi 2 MB.');
            return;
        }

        const reader = new FileReader();
        reader.onload = function (ev) {
            photos.push({ file: file, dataUrl: ev.target.result });
            if (photos.length === 1) primaryIdx = 0;
            render();
        };
        reader.readAsDataURL(file);
    });

    function render() {
        /* Counter */
        counter.textContent = photos.length + ' / ' + MAX + ' foto dipilih';
        counter.style.display  = photos.length ? 'block' : 'none';

        /* Grid */
        grid.style.display = photos.length ? 'grid' : 'none';
        grid.innerHTML     = '';

        photos.forEach(function (p, i) {
            const wrap = document.createElement('div');
            wrap.style.cssText = 'position:relative;aspect-ratio:1;border-radius:8px;overflow:hidden;border:1px solid #e5e7eb;';

            /* Thumbnail */
            const img = document.createElement('img');
            img.src = p.dataUrl;
            img.alt = 'Foto ' + (i + 1);
            img.style.cssText = 'width:100%;height:100%;object-fit:cover;display:block;';
            wrap.appendChild(img);

            /* Badge utama */
            if (i === primaryIdx) {
                const badge = document.createElement('span');
                badge.textContent = 'Utama';
                badge.style.cssText = 'position:absolute;top:4px;left:4px;background:#2563eb;color:#fff;'
                    + 'font-size:10px;padding:2px 6px;border-radius:4px;font-weight:600;';
                wrap.appendChild(badge);
            }

            /* Tombol hapus */
            const btnDel = document.createElement('button');
            btnDel.type = 'button';
            btnDel.innerHTML = '&times;';
            btnDel.title = 'Hapus foto';
            btnDel.style.cssText = 'position:absolute;top:4px;right:4px;width:22px;height:22px;border-radius:50%;'
                + 'background:rgba(0,0,0,.55);border:none;color:#fff;cursor:pointer;font-size:14px;line-height:1;'
                + 'display:flex;align-items:center;justify-content:center;';
            btnDel.onclick = function () {
                photos.splice(i, 1);
                if (primaryIdx >= photos.length) primaryIdx = 0;
                render();
            };
            wrap.appendChild(btnDel);

            /* Tombol jadikan utama */
            if (i !== primaryIdx) {
                const btnPri = document.createElement('button');
                btnPri.type = 'button';
                btnPri.textContent = 'Jadikan utama';
                btnPri.style.cssText = 'position:absolute;bottom:4px;left:50%;transform:translateX(-50%);'
                    + 'background:rgba(0,0,0,.55);border:none;color:#fff;font-size:10px;padding:2px 7px;'
                    + 'border-radius:4px;cursor:pointer;white-space:nowrap;';
                btnPri.onclick = function () { primaryIdx = i; render(); };
                wrap.appendChild(btnPri);
            }

            grid.appendChild(wrap);
        });

        /* Sembunyikan dropzone jika sudah 5 */
        dropzone.style.display = photos.length >= MAX ? 'none' : 'block';

        /* Sync hidden inputs — diperlukan agar file dikirim via form POST */
        syncHiddenInputs();
    }

    function syncHiddenInputs() {
        /* Buat DataTransfer baru dan isi dengan file-file sesuai urutan,
           dengan foto "utama" di index 0 (sesuai logika AssetController) */
        const dt = new DataTransfer();
        const ordered = [photos[primaryIdx]].concat(
            photos.filter(function (_, i) { return i !== primaryIdx; })
        );

        ordered.forEach(function (p) {
            if (p) dt.items.add(p.file);
        });

        /* Buat satu <input type="file" name="fotos[]"> tunggal dengan semua file */
        hiddenWrap.innerHTML = '';
        const inp = document.createElement('input');
        inp.type     = 'file';
        inp.name     = 'fotos[]';
        inp.multiple = true;
        inp.style.display = 'none';
        inp.files = dt.files;
        hiddenWrap.appendChild(inp);
    }
})();
</script>
@endpush