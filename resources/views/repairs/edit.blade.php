@extends('layouts.app')
@section('title', 'Update Perbaikan')
@section('page-title', 'Perbaikan Aset')

@section('content')
<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;">
    <div>
        <h1>Update Laporan Perbaikan</h1>
        <p>Kode: <strong>{{ $repair->kode_perbaikan }}</strong></p>
    </div>
    <a href="{{ route('repairs.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card" style="max-width:700px;">
    <div class="card-body">

        <!-- Info Aset -->
        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:14px 16px;margin-bottom:20px;">
            <p style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#94a3b8;margin-bottom:10px;">Info Aset</p>
            <div class="form-grid" style="gap:12px;">
                <div>
                    <p style="font-size:12px;color:#64748b;">Nama Barang</p>
                    <p style="font-weight:600;font-size:14px;">{{ $repair->asset->nama_barang ?? '-' }}</p>
                </div>
                <div>
                    <p style="font-size:12px;color:#64748b;">Lokasi</p>
                    <p style="font-weight:600;font-size:14px;">{{ $repair->asset->lokasi_barang ?? '-' }}</p>
                </div>
                <div>
                    <p style="font-size:12px;color:#64748b;">Tanggal Laporan</p>
                    <p style="font-weight:600;font-size:14px;">{{ $repair->tanggal_laporan->format('d M Y') }}</p>
                </div>
                <div>
                    <p style="font-size:12px;color:#64748b;">Dilaporkan Oleh</p>
                    <p style="font-weight:600;font-size:14px;">{{ $repair->pelapor->name ?? '-' }}</p>
                </div>
            </div>
            <div style="margin-top:12px;">
                <p style="font-size:12px;color:#64748b;margin-bottom:4px;">Deskripsi Kerusakan</p>
                <p style="font-size:13.5px;color:#374151;background:#fff;border-radius:7px;padding:10px 12px;border:1px solid #e2e8f0;">{{ $repair->deskripsi_kerusakan }}</p>
            </div>
        </div>

        <form action="{{ route('repairs.update', $repair) }}" method="POST">
            @csrf @method('PUT')

            @if(auth()->user()->isPetugasPerbaikan())
            <!-- Teknisi form: update status & tindakan -->
            <div class="form-group">
                <label class="form-label">Status Perbaikan <span style="color:#dc2626;">*</span></label>
                <select name="status" class="form-control">
                    <option value="Pending" {{ old('status',$repair->status)=='Pending'?'selected':'' }}>Pending</option>
                    <option value="Sedang Diperbaiki" {{ old('status',$repair->status)=='Sedang Diperbaiki'?'selected':'' }}>Sedang Diperbaiki</option>
                    <option value="Selesai" {{ old('status',$repair->status)=='Selesai'?'selected':'' }}>Selesai</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Tindakan Perbaikan <span style="color:#dc2626;">*</span></label>
                <textarea name="tindakan_perbaikan" class="form-control {{ $errors->has('tindakan_perbaikan') ? 'is-invalid' : '' }}" rows="4" placeholder="Jelaskan tindakan perbaikan yang telah dilakukan...">{{ old('tindakan_perbaikan', $repair->tindakan_perbaikan) }}</textarea>
                @error('tindakan_perbaikan') <p class="invalid-feedback">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Biaya Perbaikan (Rp)</label>
                <input type="number" name="biaya_perbaikan" class="form-control" min="0" value="{{ old('biaya_perbaikan', $repair->biaya_perbaikan) }}" placeholder="0">
            </div>

            @else
            <!-- Admin / Super Admin form -->
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Aset <span style="color:#dc2626;">*</span></label>
                    <select name="asset_id" class="form-control">
                        @foreach($assets as $asset)
                        <option value="{{ $asset->id }}" {{ old('asset_id',$repair->asset_id)==$asset->id?'selected':'' }}>{{ $asset->nama_barang }} ({{ $asset->kode_barang }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status Perbaikan <span style="color:#dc2626;">*</span></label>
                    <select name="status" class="form-control">
                        <option value="Pending" {{ old('status',$repair->status)=='Pending'?'selected':'' }}>Pending</option>
                        <option value="Sedang Diperbaiki" {{ old('status',$repair->status)=='Sedang Diperbaiki'?'selected':'' }}>Sedang Diperbaiki</option>
                        <option value="Selesai" {{ old('status',$repair->status)=='Selesai'?'selected':'' }}>Selesai</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi Kerusakan <span style="color:#dc2626;">*</span></label>
                <textarea name="deskripsi_kerusakan" class="form-control" rows="3">{{ old('deskripsi_kerusakan', $repair->deskripsi_kerusakan) }}</textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Tindakan Perbaikan</label>
                <textarea name="tindakan_perbaikan" class="form-control" rows="3" placeholder="Tindakan yang telah dilakukan...">{{ old('tindakan_perbaikan', $repair->tindakan_perbaikan) }}</textarea>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Ditangani Oleh</label>
                    <select name="ditangani_oleh" class="form-control">
                        <option value="">-- Pilih Petugas --</option>
                        @foreach($teknisi as $t)
                        <option value="{{ $t->id }}" {{ old('ditangani_oleh',$repair->ditangani_oleh)==$t->id?'selected':'' }}>{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Biaya Perbaikan (Rp)</label>
                    <input type="number" name="biaya_perbaikan" class="form-control" min="0" value="{{ old('biaya_perbaikan', $repair->biaya_perbaikan) }}">
                </div>
            </div>
            @endif

            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px;">
                <a href="{{ route('repairs.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
