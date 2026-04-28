@extends('layouts.app')
@section('title', 'Edit Aset')
@section('page-title', 'Edit Aset')

@section('content')
<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;">
    <div>
        <h1>Edit Aset</h1>
        <p>Perbarui data aset: <strong>{{ $asset->nama_barang }}</strong></p>
    </div>
    <a href="{{ route('assets.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('assets.update', $asset) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <p style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#2563eb;margin-bottom:16px;padding-bottom:8px;border-bottom:1.5px solid #eff6ff;">Informasi Barang</p>

            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:10px 14px;margin-bottom:16px;font-size:13px;">
                <span style="color:#64748b;">Kode Barang:</span>
                <code style="font-weight:700;color:#1e293b;margin-left:6px;">{{ $asset->kode_barang }}</code>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Nama Barang <span style="color:#dc2626;">*</span></label>
                    <input type="text" name="nama_barang" class="form-control {{ $errors->has('nama_barang') ? 'is-invalid' : '' }}" value="{{ old('nama_barang', $asset->nama_barang) }}">
                    @error('nama_barang') <p class="invalid-feedback">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori <span style="color:#dc2626;">*</span></label>
                    <select name="kategori" class="form-control">
                        @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ old('kategori', $asset->kategori)==$cat?'selected':'' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Lokasi Barang <span style="color:#dc2626;">*</span></label>
                    <input type="text" name="lokasi_barang" class="form-control" value="{{ old('lokasi_barang', $asset->lokasi_barang) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Unit Kerja <span style="color:#dc2626;">*</span></label>
                    <select name="unit_kerja" class="form-control" {{ auth()->user()->isAdminUnit() ? 'disabled' : '' }}>
                        @foreach($units as $unit)
                        <option value="{{ $unit }}" {{ old('unit_kerja', $asset->unit_kerja)==$unit?'selected':'' }}>{{ $unit }}</option>
                        @endforeach
                    </select>
                    @if(auth()->user()->isAdminUnit())
                    <input type="hidden" name="unit_kerja" value="{{ $asset->unit_kerja }}">
                    @endif
                </div>
                <div class="form-group">
                    <label class="form-label">Jumlah Barang <span style="color:#dc2626;">*</span></label>
                    <input type="number" name="jumlah_barang" class="form-control" min="1" value="{{ old('jumlah_barang', $asset->jumlah_barang) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Kondisi Barang <span style="color:#dc2626;">*</span></label>
                    <select name="kondisi_barang" class="form-control">
                        @foreach(['Baik','Rusak Ringan','Rusak Berat'] as $k)
                        <option value="{{ $k }}" {{ old('kondisi_barang', $asset->kondisi_barang)==$k?'selected':'' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Sumber Dana <span style="color:#dc2626;">*</span></label>
                    <select name="sumber_dana" class="form-control">
                        @foreach(['Dana Yayasan','Dana BOS','Hibah','Lainnya'] as $sd)
                        <option value="{{ $sd }}" {{ old('sumber_dana', $asset->sumber_dana)==$sd?'selected':'' }}>{{ $sd }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Harga Barang (Rp) <span style="color:#dc2626;">*</span></label>
                    <input type="number" name="harga_barang" class="form-control" min="0" value="{{ old('harga_barang', $asset->harga_barang) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Pengadaan <span style="color:#dc2626;">*</span></label>
                    <input type="date" name="tanggal_pengadaan" class="form-control" value="{{ old('tanggal_pengadaan', $asset->tanggal_pengadaan?->format('Y-m-d')) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Foto Barang (Opsional)</label>
                    @if($asset->foto)
                    <div style="margin-bottom:8px;">
                        <img src="{{ Storage::url($asset->foto) }}" alt="foto" style="height:60px;border-radius:6px;border:1px solid #e2e8f0;">
                        <p class="form-hint">Upload baru untuk mengganti foto lama</p>
                    </div>
                    @endif
                    <input type="file" name="foto" class="form-control" accept="image/*">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Keterangan Tambahan</label>
                <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $asset->keterangan) }}</textarea>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px;">
                <a href="{{ route('assets.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
