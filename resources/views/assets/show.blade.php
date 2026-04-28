@extends('layouts.app')
@section('title', 'Detail Aset')
@section('page-title', 'Daftar Aset')

@section('content')
<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;">
    <div>
        <h1>Detail Aset</h1>
        <p>Informasi lengkap aset <strong>{{ $asset->nama_barang }}</strong></p>
    </div>
    <div style="display:flex;gap:10px;">
        @if(!auth()->user()->isKepalayayasan())
        <a href="{{ route('assets.edit', $asset) }}" class="btn btn-primary"><i class="fas fa-pen"></i> Edit</a>
        @endif
        <a href="{{ route('assets.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;align-items:start;">

    <!-- Main Info -->
    <div style="display:flex;flex-direction:column;gap:20px;">
        <div class="card">
            <div class="card-body">
                <p style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#2563eb;margin-bottom:16px;padding-bottom:8px;border-bottom:1.5px solid #eff6ff;">Informasi Barang</p>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Kode Barang</p>
                        <code style="font-size:14px;font-weight:700;background:#f1f5f9;padding:4px 10px;border-radius:6px;">{{ $asset->kode_barang }}</code>
                    </div>
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Nama Barang</p>
                        <p style="font-weight:600;font-size:15px;">{{ $asset->nama_barang }}</p>
                    </div>
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Kategori</p>
                        <p style="font-weight:500;">{{ $asset->kategori }}</p>
                    </div>
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Lokasi Barang</p>
                        <p style="font-weight:500;">{{ $asset->lokasi_barang }}</p>
                    </div>
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Unit Kerja</p>
                        <p style="font-weight:500;">{{ $asset->unit_kerja }}</p>
                    </div>
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Jumlah Barang</p>
                        <p style="font-weight:600;font-size:18px;">{{ $asset->jumlah_barang }} <span style="font-size:13px;font-weight:400;color:#64748b;">unit</span></p>
                    </div>
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Kondisi Barang</p>
                        <span class="badge {{ $asset->kondisi_barang === 'Baik' ? 'badge-success' : ($asset->kondisi_barang === 'Rusak Ringan' ? 'badge-warning' : 'badge-danger') }}">
                            {{ $asset->kondisi_barang }}
                        </span>
                    </div>
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Sumber Dana</p>
                        <p style="font-weight:500;">{{ $asset->sumber_dana }}</p>
                    </div>
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Harga Barang</p>
                        <p style="font-weight:700;font-size:16px;color:#2563eb;">Rp {{ number_format($asset->harga_barang, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Tanggal Pengadaan</p>
                        <p style="font-weight:500;">{{ $asset->tanggal_pengadaan ? $asset->tanggal_pengadaan->format('d M Y') : '-' }}</p>
                    </div>
                    @if($asset->keterangan)
                    <div style="grid-column:1/-1;">
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Keterangan</p>
                        <p style="font-size:13.5px;color:#374151;background:#f8fafc;border-radius:8px;padding:10px 12px;border:1px solid #e2e8f0;">{{ $asset->keterangan }}</p>
                    </div>
                    @endif
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Ditambahkan Oleh</p>
                        <p style="font-weight:500;">{{ $asset->creator->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Tanggal Input</p>
                        <p style="font-weight:500;">{{ $asset->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Repair History -->
        <div class="card">
            <div class="card-header" style="padding:18px 20px 14px;display:flex;align-items:center;justify-content:space-between;">
                <h2>Riwayat Perbaikan</h2>
                @if(auth()->user()->canAccess('perbaikan_aset') && !auth()->user()->isPetugasPerbaikan())
                <a href="{{ route('repairs.create') }}?asset_id={{ $asset->id }}" class="btn btn-outline btn-sm"><i class="fas fa-plus"></i> Laporkan</a>
                @endif
            </div>
            <div class="card-body" style="padding:0 20px 16px;">
                @forelse($repairs as $repair)
                <div style="padding:12px 0;border-bottom:1px solid #f1f5f9;">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:6px;">
                        <div>
                            <code style="font-size:12px;background:#f1f5f9;padding:2px 7px;border-radius:5px;">{{ $repair->kode_perbaikan }}</code>
                            <span class="badge {{ $repair->status === 'Selesai' ? 'badge-success' : ($repair->status === 'Sedang Diperbaiki' ? 'badge-info' : 'badge-warning') }}" style="margin-left:6px;font-size:11px;">{{ $repair->status }}</span>
                        </div>
                        <span style="font-size:12px;color:#94a3b8;">{{ $repair->tanggal_laporan->format('d M Y') }}</span>
                    </div>
                    <p style="font-size:13px;color:#374151;margin-bottom:3px;">{{ $repair->deskripsi_kerusakan }}</p>
                    @if($repair->tindakan_perbaikan)
                    <p style="font-size:12px;color:#64748b;"><i class="fas fa-wrench" style="margin-right:4px;"></i>{{ $repair->tindakan_perbaikan }}</p>
                    @endif
                    @if($repair->teknisi)
                    <p style="font-size:12px;color:#94a3b8;margin-top:3px;"><i class="fas fa-user" style="margin-right:4px;"></i>{{ $repair->teknisi->name }}</p>
                    @endif
                </div>
                @empty
                <div style="padding:24px 0;text-align:center;color:#94a3b8;font-size:13px;">
                    <i class="fas fa-check-circle" style="font-size:24px;display:block;margin-bottom:8px;color:#d1fae5;"></i>
                    Belum ada riwayat perbaikan
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Foto Sidebar -->
    <div class="card">
        <div class="card-body" style="text-align:center;">
            <p style="font-size:12px;color:#94a3b8;margin-bottom:12px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Foto Aset</p>
            @if($asset->foto)
            <img src="{{ Storage::url($asset->foto) }}" alt="Foto {{ $asset->nama_barang }}"
                 style="width:100%;border-radius:10px;border:1px solid #e2e8f0;object-fit:cover;">
            @else
            <div style="width:100%;aspect-ratio:1;background:#f8fafc;border-radius:10px;border:2px dashed #e2e8f0;display:flex;flex-direction:column;align-items:center;justify-content:center;color:#cbd5e1;">
                <i class="fas fa-image" style="font-size:36px;margin-bottom:8px;"></i>
                <p style="font-size:12px;">Tidak ada foto</p>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection
