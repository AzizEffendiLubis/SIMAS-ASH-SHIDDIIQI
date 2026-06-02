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
                    <label class="form-label">Lokasi Barang</label>
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
                <div class="form-group">
                    {{-- Multi-foto: name="fotos[]" sesuai AssetController::store() --}}
                    <label class="form-label">Foto Barang</label>
                    <input type="file" name="fotos[]" class="form-control" accept="image/jpg,image/jpeg,image/png,image/webp" multiple>
                    <p class="form-hint">Format JPG/PNG/WEBP, maks. 2MB per foto, hingga 5 foto</p>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Spesifikasi</label>
                <input type="text" name="spesifikasi" class="form-control {{ $errors->has('spesifikasi') ? 'is-invalid' : '' }}" placeholder="Contoh: RAM 8GB, SSD 512GB" value="{{ old('spesifikasi') }}">
                @error('spesifikasi') <p class="invalid-feedback">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Dasar / Keterangan Persetujuan</label>
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