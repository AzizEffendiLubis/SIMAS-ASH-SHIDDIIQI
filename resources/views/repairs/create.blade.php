@extends('layouts.app')
@section('title', 'Laporkan Kerusakan')
@section('page-title', 'Perbaikan Aset')

@section('content')
<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;">
    <div>
        <h1>Laporkan Kerusakan Aset</h1>
        <p>Isi formulir berikut untuk melaporkan kerusakan aset</p>
    </div>
    <a href="{{ route('repairs.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card" style="max-width:700px;">
    <div class="card-body">
        <form action="{{ route('repairs.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label">Pilih Aset yang Rusak <span style="color:#dc2626;">*</span></label>
                <select name="asset_id" class="form-control {{ $errors->has('asset_id') ? 'is-invalid' : '' }}">
                    <option value="">-- Pilih Aset --</option>
                    @foreach($allAssets as $asset)
                    <option value="{{ $asset->id }}" {{ old('asset_id')==$asset->id?'selected':'' }}>
                        {{ $asset->nama_barang }} – {{ $asset->kode_barang }} ({{ $asset->lokasi_barang }})
                    </option>
                    @endforeach
                </select>
                @error('asset_id') <p class="invalid-feedback">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi Kerusakan <span style="color:#dc2626;">*</span></label>
                <textarea name="deskripsi_kerusakan" class="form-control {{ $errors->has('deskripsi_kerusakan') ? 'is-invalid' : '' }}" rows="4" placeholder="Jelaskan kerusakan yang terjadi secara detail...">{{ old('deskripsi_kerusakan') }}</textarea>
                @error('deskripsi_kerusakan') <p class="invalid-feedback">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Tugaskan Petugas Perbaikan</label>
                <select name="ditangani_oleh" class="form-control">
                    <option value="">-- Pilih Petugas (Opsional) --</option>
                    @foreach($teknisi as $t)
                    <option value="{{ $t->id }}" {{ old('ditangani_oleh')==$t->id?'selected':'' }}>{{ $t->name }} ({{ $t->unit_kerja }})</option>
                    @endforeach
                </select>
                <p class="form-hint">Jika tidak dipilih, dapat ditugaskan nanti oleh Admin.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Foto Kerusakan (Opsional)</label>
                <input type="file" name="foto_kerusakan" class="form-control" accept="image/*">
                <p class="form-hint">Format JPG/PNG, maks. 2MB</p>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px;">
                <a href="{{ route('repairs.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Kirim Laporan</button>
            </div>
        </form>
    </div>
</div>
@endsection
