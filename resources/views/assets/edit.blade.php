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

            <div class="form-group">
                {{-- Tambah foto baru: name="fotos_baru[]" sesuai AssetController::update() --}}
                <label class="form-label">Tambah Foto Baru</label>
                <input type="file" name="fotos_baru[]" class="form-control" accept="image/jpg,image/jpeg,image/png,image/webp" multiple>
                <p class="form-hint">Format JPG/PNG/WEBP, maks. 2MB per foto, hingga 5 foto</p>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px;">
                <a href="{{ route('assets.show', $asset) }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection