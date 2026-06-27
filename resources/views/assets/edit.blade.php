@extends('layouts.app')
@section('title', 'Edit Aset')
@section('page-title', 'Edit Aset')

@section('content')
<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;">
    <div>
        <h1>Edit Aset</h1>
        <p>Perbarui data aset: <strong>{{ $asset->nama_barang }}</strong></p>
    </div>
    <a href="{{ route('assets.show', $asset) }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('assets.update', $asset) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            {{-- ============================================================
                 INFORMASI UTAMA — tidak bisa diubah setelah disimpan.
                 Dokumen: "Informasi utama aset tidak dapat diubah setelah disimpan."
                 Ditampilkan sebagai read-only untuk transparansi kepada admin.
                 ============================================================ --}}
            <p style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#2563eb;margin-bottom:16px;padding-bottom:8px;border-bottom:1.5px solid #eff6ff;">Informasi Barang <span style="font-weight:400;color:#64748b;text-transform:none;letter-spacing:0;">(tidak dapat diubah)</span></p>

            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:10px 14px;margin-bottom:16px;font-size:13px;">
                <span style="color:#64748b;">Kode Aset:</span>
                <code style="font-weight:700;color:#1e293b;margin-left:6px;">{{ $asset->kode_aset }}</code>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" class="form-control" value="{{ $asset->nama_barang }}" disabled>
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <input type="text" class="form-control" value="{{ $asset->kategori }}" disabled>
                </div>
                <div class="form-group">
                    <label class="form-label">Unit Kerja</label>
                    <input type="text" class="form-control" value="{{ $asset->unit->nama_unit ?? '-' }}" disabled>
                </div>
                <div class="form-group">
                    <label class="form-label">Jumlah Barang</label>
                    <input type="number" class="form-control" value="{{ $asset->jumlah_barang }}" disabled>
                </div>
                <div class="form-group">
                    <label class="form-label">Harga Barang (Rp)</label>
                    <input type="number" class="form-control" value="{{ $asset->harga_barang }}" disabled>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Pengadaan</label>
                    <input type="date" class="form-control" value="{{ $asset->tanggal_pengadaan?->format('Y-m-d') }}" disabled>
                </div>
                <div class="form-group">
                    <label class="form-label">Sumber Dana</label>
                    <input type="text" class="form-control" value="{{ $asset->fundingSource->nama_sumber ?? '-' }}" disabled>
                </div>
                <div class="form-group">
                    <label class="form-label">Satuan</label>
                    <input type="text" class="form-control" value="{{ $asset->satuan->nama_satuan ?? '-' }}" disabled>
                </div>
            </div>

            {{-- ============================================================
                 FIELD YANG BISA DIEDIT:
                 1. kondisi_barang  — semua admin
                 2. foto            — semua admin (tambah/hapus)
                 3. lokasi_barang   — HANYA aset unit Yayasan (is_unit_yayasan)
                 Dokumen: "Hak edit dibatasi hanya untuk foto aset dan kondisi aset."
                 Dokumen: "Aset unit Yayasan dapat diubah lokasi penempatannya."
                 ============================================================ --}}
            <p style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#2563eb;margin-bottom:16px;margin-top:24px;padding-bottom:8px;border-bottom:1.5px solid #eff6ff;">Data yang Dapat Diperbarui</p>

            <div class="form-grid">
                <div class="form-group">
                    {{-- Kondisi: enum aktif|rusak|hilang|habis_pakai sesuai migration --}}
                    <label class="form-label">Kondisi Barang <span style="color:#dc2626;">*</span></label>
                    <select name="kondisi_barang" class="form-control {{ $errors->has('kondisi_barang') ? 'is-invalid' : '' }}">
                        @foreach(['aktif' => 'Aktif', 'rusak' => 'Rusak', 'hilang' => 'Hilang', 'habis_pakai' => 'Habis Pakai'] as $val => $label)
                        <option value="{{ $val }}" {{ old('kondisi_barang', $asset->kondisi_barang)==$val?'selected':'' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('kondisi_barang') <p class="invalid-feedback">{{ $message }}</p> @enderror
                </div>

                @if($asset->is_unit_yayasan)
                {{-- Lokasi hanya bisa diedit untuk aset unit Yayasan --}}
                <div class="form-group">
                    <label class="form-label">Lokasi Barang</label>
                    <input type="text" name="lokasi_barang" class="form-control {{ $errors->has('lokasi_barang') ? 'is-invalid' : '' }}" placeholder="Contoh: Ruang Rapat Lantai 2" value="{{ old('lokasi_barang', $asset->lokasi_barang) }}">
                    @error('lokasi_barang') <p class="invalid-feedback">{{ $message }}</p> @enderror
                </div>
                @else
                <div class="form-group">
                    <label class="form-label">Lokasi Barang</label>
                    <input type="text" class="form-control" value="{{ $asset->lokasi_barang ?? '-' }}" disabled>
                    <p class="form-hint">Lokasi hanya bisa diubah untuk aset unit Yayasan.</p>
                </div>
                @endif
            </div>

            <div class="form-group">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3" placeholder="Catatan tambahan...">{{ old('keterangan', $asset->keterangan) }}</textarea>
            </div>

            {{-- ── Manajemen Foto ───────────────────────────────────────── --}}
            <p style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#2563eb;margin-bottom:16px;margin-top:24px;padding-bottom:8px;border-bottom:1.5px solid #eff6ff;">Foto Aset</p>

            @if($asset->photos->isNotEmpty())
            <div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:16px;">
                @foreach($asset->photos as $foto)
                <div style="position:relative;display:inline-block;">
                    <img src="{{ Storage::url($foto->file_path) }}" alt="foto aset"
                         style="height:80px;width:80px;object-fit:cover;border-radius:6px;border:2px solid {{ $foto->is_primary ? '#2563eb' : '#e2e8f0' }};">
                    @if($foto->is_primary)
                    <span style="position:absolute;top:4px;left:4px;background:#2563eb;color:#fff;font-size:9px;padding:1px 5px;border-radius:4px;">Utama</span>
                    @endif
                    <div style="margin-top:4px;display:flex;gap:4px;">
                        <label style="font-size:11px;display:flex;align-items:center;gap:3px;cursor:pointer;">
                            <input type="radio" name="foto_utama_id" value="{{ $foto->id }}" {{ $foto->is_primary?'checked':'' }}> Utama
                        </label>
                        <label style="font-size:11px;display:flex;align-items:center;gap:3px;cursor:pointer;">
                            <input type="checkbox" name="hapus_foto[]" value="{{ $foto->id }}"> Hapus
                        </label>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p style="color:#94a3b8;font-size:13px;margin-bottom:12px;">Belum ada foto untuk aset ini.</p>
            @endif

            <div class="form-group" id="foto-baru-group">
                <label class="form-label">Tambah Foto Baru</label>

                {{-- Grid preview foto baru yang dipilih --}}
                <div id="foto-baru-grid"
                    style="display:none; grid-template-columns:repeat(5,1fr); gap:10px; margin-bottom:10px;">
                </div>
                <p id="foto-baru-counter"
                style="display:none; font-size:12px; color:#6b7280; text-align:right; margin-bottom:8px;">
                </p>

                {{-- Dropzone --}}
                <div id="foto-baru-dropzone"
                    onclick="document.getElementById('foto-baru-picker').click()"
                    style="border:1.5px dashed #d1d5db; border-radius:10px; padding:1.5rem;
                            text-align:center; cursor:pointer; background:#f9fafb;
                            transition:border-color .15s,background .15s;"
                    onmouseover="this.style.borderColor='#2563eb';this.style.background='#eff6ff'"
                    onmouseout="this.style.borderColor='#d1d5db';this.style.background='#f9fafb'">
                    <i class="fas fa-cloud-upload-alt" style="font-size:24px; color:#9ca3af; display:block; margin-bottom:6px;"></i>
                    <p style="margin:0; font-size:13px; color:#6b7280;">Klik untuk pilih foto baru</p>
                    <span style="font-size:11px; color:#9ca3af;">JPG / PNG / WEBP · maks. 2 MB per foto</span>
                    <span id="foto-baru-sisa-hint" style="display:block; font-size:11px; color:#9ca3af; margin-top:2px;"></span>
                </div>

                <input type="file" id="foto-baru-picker"
                    accept="image/jpg,image/jpeg,image/png,image/webp"
                    style="display:none">

                {{-- Hidden inputs yang dikirim ke controller --}}
                <div id="foto-baru-hidden"></div>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px;">
                <a href="{{ route('assets.show', $asset) }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const MAX_TOTAL   = 5;
    const existingCount = {{ $asset->photos->count() }};
    const photos      = [];

    const picker      = document.getElementById('foto-baru-picker');
    const grid        = document.getElementById('foto-baru-grid');
    const counter     = document.getElementById('foto-baru-counter');
    const dropzone    = document.getElementById('foto-baru-dropzone');
    const hiddenWrap  = document.getElementById('foto-baru-hidden');
    const sisaHint    = document.getElementById('foto-baru-sisa-hint');

    // Hitung slot tersisa: total maks 5 dikurangi foto lama yang belum dicentang hapus
    function getHapusCount() {
        return document.querySelectorAll('input[name="hapus_foto[]"]:checked').length;
    }

    function getMaxBaru() {
        const active = existingCount - getHapusCount();
        return Math.max(0, MAX_TOTAL - active);
    }

    function updateSisaHint() {
        const sisa = getMaxBaru() - photos.length;
        if (sisa > 0) {
            sisaHint.textContent = 'Dapat menambah ' + sisa + ' foto lagi (total maks. ' + MAX_TOTAL + ')';
        } else {
            sisaHint.textContent = 'Batas foto tercapai (maks. ' + MAX_TOTAL + ' foto per aset)';
        }
    }

    // Update dropzone jika checkbox hapus diubah
    document.querySelectorAll('input[name="hapus_foto[]"]').forEach(function (cb) {
        cb.addEventListener('change', function () {
            render();
        });
    });

    picker.addEventListener('change', function (e) {
        const file = e.target.files[0];
        picker.value = '';
        if (!file) return;

        if (photos.length >= getMaxBaru()) {
            alert('Tidak dapat menambah foto. Total foto aset tidak boleh melebihi ' + MAX_TOTAL + ' foto.');
            return;
        }
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran foto "' + file.name + '" melebihi 2 MB.');
            return;
        }

        const reader = new FileReader();
        reader.onload = function (ev) {
            photos.push({ file: file, dataUrl: ev.target.result });
            render();
        };
        reader.readAsDataURL(file);
    });

    function render() {
        const maxBaru = getMaxBaru();

        counter.textContent   = photos.length + ' foto baru dipilih';
        counter.style.display = photos.length ? 'block' : 'none';
        grid.style.display    = photos.length ? 'grid'  : 'none';
        grid.innerHTML        = '';

        // Sembunyikan dropzone jika slot penuh
        dropzone.style.display = photos.length >= maxBaru ? 'none' : 'block';
        updateSisaHint();

        photos.forEach(function (p, i) {
            const wrap = document.createElement('div');
            wrap.style.cssText = 'position:relative;aspect-ratio:1;border-radius:8px;'
                               + 'overflow:hidden;border:1px solid #e5e7eb;';

            const img = document.createElement('img');
            img.src   = p.dataUrl;
            img.alt   = 'Foto baru ' + (i + 1);
            img.style.cssText = 'width:100%;height:100%;object-fit:cover;display:block;';
            wrap.appendChild(img);

            // Badge urutan
            const badge = document.createElement('span');
            badge.textContent = 'Baru ' + (i + 1);
            badge.style.cssText = 'position:absolute;top:4px;left:4px;background:#0891b2;'
                + 'color:#fff;font-size:10px;padding:2px 6px;border-radius:4px;font-weight:600;';
            wrap.appendChild(badge);

            // Tombol hapus
            const btnDel = document.createElement('button');
            btnDel.type  = 'button';
            btnDel.innerHTML = '&times;';
            btnDel.title = 'Batalkan foto ini';
            btnDel.style.cssText = 'position:absolute;top:4px;right:4px;width:22px;height:22px;'
                + 'border-radius:50%;background:rgba(0,0,0,.55);border:none;color:#fff;'
                + 'cursor:pointer;font-size:14px;line-height:1;display:flex;'
                + 'align-items:center;justify-content:center;';
            btnDel.onclick = function () {
                photos.splice(i, 1);
                render();
            };
            wrap.appendChild(btnDel);

            grid.appendChild(wrap);
        });

        syncHiddenInputs();
    }

    function syncHiddenInputs() {
        const dt = new DataTransfer();
        photos.forEach(function (p) { dt.items.add(p.file); });

        hiddenWrap.innerHTML = '';
        if (photos.length > 0) {
            const inp    = document.createElement('input');
            inp.type     = 'file';
            inp.name     = 'fotos_baru[]';
            inp.multiple = true;
            inp.style.display = 'none';
            inp.files    = dt.files;
            hiddenWrap.appendChild(inp);
        }
    }

    // Init hint saat halaman pertama kali load
    updateSisaHint();
})();
</script>
@endpush
@endsection