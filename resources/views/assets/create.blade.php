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
                    <label class="form-label">Unit Kerja <span style="color:#dc2626;">*</span></label>
                    <select name="unit_kerja" class="form-control {{ $errors->has('unit_kerja') ? 'is-invalid' : '' }}">
                        <option value="">-- Pilih Unit --</option>
                        @foreach($units as $unit)
                        <option value="{{ $unit }}" {{ (old('unit_kerja', auth()->user()->unit_kerja))==$unit?'selected':'' }}>{{ $unit }}</option>
                        @endforeach
                    </select>
                    @error('unit_kerja') <p class="invalid-feedback">{{ $message }}</p> @enderror
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
                    <label class="form-label">Kondisi Barang <span style="color:#dc2626;">*</span></label>
                    <select name="kondisi_barang" class="form-control {{ $errors->has('kondisi_barang') ? 'is-invalid' : '' }}">
                        <option value="Baik" {{ old('kondisi_barang','Baik')=='Baik'?'selected':'' }}>Baik</option>
                        <option value="Rusak Ringan" {{ old('kondisi_barang')=='Rusak Ringan'?'selected':'' }}>Rusak Ringan</option>
                        <option value="Rusak Berat" {{ old('kondisi_barang')=='Rusak Berat'?'selected':'' }}>Rusak Berat</option>
                    </select>
                    @error('kondisi_barang') <p class="invalid-feedback">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Sumber Dana <span style="color:#dc2626;">*</span></label>
                    <select name="sumber_dana" class="form-control {{ $errors->has('sumber_dana') ? 'is-invalid' : '' }}">
                        @foreach(['Dana Yayasan','Dana BOS','Hibah','Lainnya'] as $sd)
                        <option value="{{ $sd }}" {{ old('sumber_dana')==$sd?'selected':'' }}>{{ $sd }}</option>
                        @endforeach
                    </select>
                    @error('sumber_dana') <p class="invalid-feedback">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Harga Barang (Rp) <span style="color:#dc2626;">*</span></label>
                    <input type="number" name="harga_barang" class="form-control {{ $errors->has('harga_barang') ? 'is-invalid' : '' }}" min="0" placeholder="0" value="{{ old('harga_barang') }}">
                    @error('harga_barang') <p class="invalid-feedback">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Pengadaan <span style="color:#dc2626;">*</span></label>
                    <input type="date" name="tanggal_pengadaan" class="form-control {{ $errors->has('tanggal_pengadaan') ? 'is-invalid' : '' }}" value="{{ old('tanggal_pengadaan', date('Y-m-d')) }}">
                    @error('tanggal_pengadaan') <p class="invalid-feedback">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Foto Barang</label>
                    <input type="file" name="foto" class="form-control" accept="image/*">
                    <p class="form-hint">Format JPG/PNG, maks. 2MB</p>
                </div>
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
