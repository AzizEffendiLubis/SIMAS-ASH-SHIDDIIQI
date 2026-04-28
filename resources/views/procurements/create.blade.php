@extends('layouts.app')
@section('title', 'Ajukan Pengadaan')
@section('page-title', 'Pengadaan Aset')

@section('content')
<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;">
    <div>
        <h1>Ajukan Pengadaan Aset</h1>
        <p>Buat pengajuan pengadaan aset baru untuk unit Anda</p>
    </div>
    <a href="{{ route('procurements.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card" style="max-width:700px;">
    <div class="card-body">
        <form action="{{ route('procurements.store') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Nama Barang <span style="color:#dc2626;">*</span></label>
                    <input type="text" name="nama_barang" class="form-control {{ $errors->has('nama_barang') ? 'is-invalid' : '' }}" placeholder="Nama barang yang dibutuhkan" value="{{ old('nama_barang') }}">
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
                    <label class="form-label">Unit Kerja <span style="color:#dc2626;">*</span></label>
                    <select name="unit_kerja" class="form-control {{ $errors->has('unit_kerja') ? 'is-invalid' : '' }}" {{ auth()->user()->isAdminUnit() ? 'disabled' : '' }}>
                        @foreach($units as $unit)
                        <option value="{{ $unit }}" {{ old('unit_kerja', auth()->user()->unit_kerja)==$unit?'selected':'' }}>{{ $unit }}</option>
                        @endforeach
                    </select>
                    @if(auth()->user()->isAdminUnit())
                    <input type="hidden" name="unit_kerja" value="{{ auth()->user()->unit_kerja }}">
                    @endif
                    @error('unit_kerja') <p class="invalid-feedback">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Jumlah <span style="color:#dc2626;">*</span></label>
                    <input type="number" name="jumlah" class="form-control {{ $errors->has('jumlah') ? 'is-invalid' : '' }}" min="1" value="{{ old('jumlah', 1) }}">
                    @error('jumlah') <p class="invalid-feedback">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Estimasi Harga (Rp) <span style="color:#dc2626;">*</span></label>
                    <input type="number" name="estimasi_harga" class="form-control {{ $errors->has('estimasi_harga') ? 'is-invalid' : '' }}" min="0" placeholder="0" value="{{ old('estimasi_harga') }}">
                    @error('estimasi_harga') <p class="invalid-feedback">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Sumber Dana <span style="color:#dc2626;">*</span></label>
                    <select name="sumber_dana" class="form-control {{ $errors->has('sumber_dana') ? 'is-invalid' : '' }}">
                        @foreach(['Dana Yayasan','Dana BOS','Hibah','Lainnya'] as $sd)
                        <option value="{{ $sd }}" {{ old('sumber_dana')==$sd?'selected':'' }}>{{ $sd }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Alasan Pengadaan <span style="color:#dc2626;">*</span></label>
                <textarea name="alasan_pengadaan" class="form-control {{ $errors->has('alasan_pengadaan') ? 'is-invalid' : '' }}" rows="4" placeholder="Jelaskan alasan dan urgensi pengadaan barang ini...">{{ old('alasan_pengadaan') }}</textarea>
                @error('alasan_pengadaan') <p class="invalid-feedback">{{ $message }}</p> @enderror
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px;">
                <a href="{{ route('procurements.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Ajukan Pengadaan</button>
            </div>
        </form>
    </div>
</div>
@endsection
