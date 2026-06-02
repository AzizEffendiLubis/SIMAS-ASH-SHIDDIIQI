@extends('layouts.app')
@section('title', 'Daftar Aset')
@section('page-title', 'Daftar Aset')

@section('content')
<div class="page-header" style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;">
    <div>
        <h1>Daftar Aset</h1>
        <p>Kelola seluruh aset {{ auth()->user()->isAdminUnit() ? 'unit '.auth()->user()->unit->nama_unit : 'pesantren' }}</p>
    </div>
    {{-- Tombol Tambah: hanya Admin Utama & Admin Unit --}}
    {{-- Dokumen: "Kepala Yayasan hanya berperan sebagai pihak monitoring." --}}
    @if(auth()->user()->canEditAset())
    <a href="{{ route('assets.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Aset
    </a>
    @endif
</div>

<!-- Filters -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-body" style="padding:16px 20px;">
        <form method="GET" action="{{ route('assets.index') }}" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
            <div class="search-bar" style="flex:1;min-width:200px;">
                <input type="text" name="search" class="form-control"
                       placeholder="Cari nama barang, kode, lokasi..."
                       value="{{ request('search') }}">
            </div>
            <div style="min-width:150px;">
                <select name="kategori" class="form-control">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('kategori') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            {{-- Filter unit: hanya Admin Utama & Kepala Yayasan --}}
            {{-- $units adalah Collection dari Unit model (Masterdata.php) --}}
            @if(auth()->user()->isAdminUtama() || auth()->user()->isKepalaYayasan())
            <div style="min-width:150px;">
                <select name="unit_id" class="form-control">
                    <option value="">Semua Unit</option>
                    @foreach($units as $unit)
                    <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>
                        {{ $unit->nama_unit }}
                    </option>
                    @endforeach
                </select>
            </div>
            @endif
            {{-- Kondisi sesuai enum di migration & model: aktif|rusak|hilang|habis_pakai --}}
            <div style="min-width:140px;">
                <select name="kondisi" class="form-control">
                    <option value="">Semua Kondisi</option>
                    <option value="aktif"       {{ request('kondisi') == 'aktif'       ? 'selected' : '' }}>Aktif</option>
                    <option value="rusak"       {{ request('kondisi') == 'rusak'       ? 'selected' : '' }}>Rusak</option>
                    <option value="hilang"      {{ request('kondisi') == 'hilang'      ? 'selected' : '' }}>Hilang</option>
                    <option value="habis_pakai" {{ request('kondisi') == 'habis_pakai' ? 'selected' : '' }}>Habis Pakai</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="height:42px;">
                <i class="fas fa-filter"></i> Filter
            </button>
            @if(request()->hasAny(['search', 'kategori', 'unit_id', 'kondisi', 'sort', 'dir']))
            <a href="{{ route('assets.index') }}" class="btn btn-outline" style="height:42px;">Reset</a>
            @endif
        </form>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-body" style="padding:0;">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:40px;">No.</th>
                        <th>Nama Barang</th>
                        <th>Kode Aset</th>
                        <th>Unit / Lokasi</th>
                        <th style="text-align:center;">Jumlah</th>
                        <th>Kondisi</th>
                        <th>Sumber Dana</th>
                        <th>Harga</th>
                        <th>Foto</th>
                        <th style="width:80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assets as $i => $asset)
                    <tr>
                        <td style="color:#94a3b8;">{{ $assets->firstItem() + $i }}</td>
                        <td>
                            <div style="font-weight:600;color:#1e293b;">{{ $asset->nama_barang }}</div>
                            <div style="font-size:12px;color:#94a3b8;">{{ $asset->kategori }}</div>
                        </td>
                        <td>
                            {{-- kode_aset, bukan kode_barang --}}
                            <code style="font-size:12px;background:#f1f5f9;padding:2px 7px;border-radius:5px;">{{ $asset->kode_aset }}</code>
                        </td>
                        <td>
                            <div>{{ $asset->lokasi_barang ?? '-' }}</div>
                            {{-- unit adalah relasi belongsTo Unit (Asset.php) --}}
                            <div style="font-size:12px;color:#94a3b8;">{{ $asset->unit->nama_unit ?? '-' }}</div>
                        </td>
                        <td style="text-align:center;font-weight:600;">{{ $asset->jumlah_barang }}</td>
                        <td>
                            {{-- kondisi_badge & kondisi_label dari accessor di Asset model --}}
                            <span class="badge {{ $asset->kondisi_badge }}">
                                {{ $asset->kondisi_label }}
                            </span>
                        </td>
                        <td style="font-size:13px;">
                            {{-- fundingSource adalah relasi belongsTo FundingSource (Asset.php) --}}
                            {{ $asset->fundingSource->nama_sumber ?? '-' }}
                        </td>
                        <td style="font-size:13px;font-weight:600;">
                            Rp {{ number_format($asset->harga_barang, 0, ',', '.') }}
                        </td>
                        <td>
                            {{-- foto_utama adalah accessor di Asset model --}}
                            @if($asset->foto_utama)
                            <img src="{{ Storage::url($asset->foto_utama->file_path) }}"
                                 alt="foto"
                                 style="width:40px;height:40px;object-fit:cover;border-radius:6px;border:1px solid #e2e8f0;">
                            @else
                            <div style="width:40px;height:40px;background:#f1f5f9;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#cbd5e1;font-size:16px;">
                                <i class="fas fa-image"></i>
                            </div>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex;gap:5px;">
                                <a href="{{ route('assets.show', $asset) }}"
                                   class="btn btn-outline btn-sm btn-icon" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                {{-- Edit: hanya Admin Utama & Admin Unit --}}
                                {{-- Dokumen: "Hak edit dibatasi hanya untuk foto dan kondisi aset." --}}
                                @if(auth()->user()->canEditAset())
                                <a href="{{ route('assets.edit', $asset) }}"
                                   class="btn btn-outline btn-sm btn-icon" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                                @endif
                                {{-- Tombol hapus TIDAK ada --}}
                                {{-- Dokumen: "Data aset tidak dapat dihapus secara permanen." --}}
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" style="text-align:center;padding:40px;color:#94a3b8;">
                            <i class="fas fa-box-open" style="font-size:32px;display:block;margin-bottom:10px;opacity:.3;"></i>
                            Tidak ada data aset
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($assets->hasPages())
        <div style="padding:14px 20px;border-top:1px solid #f1f5f9;">
            {{ $assets->links('vendor.pagination.simple') }}
        </div>
        @endif
    </div>
</div>

{{-- Modal hapus DIHAPUS --}}
{{-- Dokumen: "Data aset tidak dapat dihapus secara permanen." --}}
{{-- Controller destroy() sudah abort(403) --}}

@endsection