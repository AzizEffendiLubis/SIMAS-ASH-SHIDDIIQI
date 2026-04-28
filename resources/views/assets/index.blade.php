@extends('layouts.app')
@section('title', 'Daftar Aset')
@section('page-title', 'Daftar Aset')

@section('content')
<div class="page-header" style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;">
    <div>
        <h1>Daftar Aset</h1>
        <p>Kelola seluruh aset {{ auth()->user()->isAdminUnit() ? 'unit '.auth()->user()->unit_kerja : 'pesantren' }}</p>
    </div>
    @if(!auth()->user()->isKepalayayasan())
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
                <input type="text" name="search" class="form-control" placeholder="Cari nama barang, kode, lokasi..." value="{{ request('search') }}">
            </div>
            <div style="min-width:150px;">
                <select name="kategori" class="form-control">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('kategori')==$cat?'selected':'' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            @if(auth()->user()->isSuperAdmin() || auth()->user()->iskepalayayasan())
            <div style="min-width:150px;">
                <select name="unit_kerja" class="form-control">
                    <option value="">Semua Unit</option>
                    @foreach($units as $unit)
                    <option value="{{ $unit }}" {{ request('unit_kerja')==$unit?'selected':'' }}>{{ $unit }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div style="min-width:140px;">
                <select name="kondisi" class="form-control">
                    <option value="">Semua Kondisi</option>
                    <option value="Baik" {{ request('kondisi')=='Baik'?'selected':'' }}>Baik</option>
                    <option value="Rusak Ringan" {{ request('kondisi')=='Rusak Ringan'?'selected':'' }}>Rusak Ringan</option>
                    <option value="Rusak Berat" {{ request('kondisi')=='Rusak Berat'?'selected':'' }}>Rusak Berat</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="height:42px;">
                <i class="fas fa-filter"></i> Filter
            </button>
            @if(request()->hasAny(['search','kategori','unit_kerja','kondisi']))
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
                        <th>Kode Barang</th>
                        <th>Unit/Lokasi</th>
                        <th style="text-align:center;">Jumlah</th>
                        <th>Kondisi</th>
                        <th>Sumber Dana</th>
                        <th>Harga</th>
                        <th>Foto</th>
                        <th style="width:100px;">Aksi</th>
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
                        <td><code style="font-size:12px;background:#f1f5f9;padding:2px 7px;border-radius:5px;">{{ $asset->kode_barang }}</code></td>
                        <td>
                            <div>{{ $asset->lokasi_barang }}</div>
                            <div style="font-size:12px;color:#94a3b8;">{{ $asset->unit_kerja }}</div>
                        </td>
                        <td style="text-align:center;font-weight:600;">{{ $asset->jumlah_barang }}</td>
                        <td>
                            <span class="badge {{ $asset->kondisi_barang === 'Baik' ? 'badge-success' : ($asset->kondisi_barang === 'Rusak Ringan' ? 'badge-warning' : 'badge-danger') }}">
                                {{ $asset->kondisi_barang }}
                            </span>
                        </td>
                        <td style="font-size:13px;">{{ $asset->sumber_dana }}</td>
                        <td style="font-size:13px;font-weight:600;">Rp {{ number_format($asset->harga_barang, 0, ',', '.') }}</td>
                        <td>
                            @if($asset->foto)
                            <img src="{{ Storage::url($asset->foto) }}" alt="foto" style="width:40px;height:40px;object-fit:cover;border-radius:6px;border:1px solid #e2e8f0;">
                            @else
                            <div style="width:40px;height:40px;background:#f1f5f9;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#cbd5e1;font-size:16px;"><i class="fas fa-image"></i></div>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex;gap:5px;">
                                <a href="{{ route('assets.show', $asset) }}" class="btn btn-outline btn-sm btn-icon" title="Detail"><i class="fas fa-eye"></i></a>
                                @if(!auth()->user()->iskepalayayasan())
                                <a href="{{ route('assets.edit', $asset) }}" class="btn btn-outline btn-sm btn-icon" title="Edit"><i class="fas fa-pen"></i></a>
                                @if(auth()->user()->isSuperAdmin())
                                <button class="btn btn-outline btn-sm btn-icon" style="color:#dc2626;" title="Hapus" onclick="confirmDelete({{ $asset->id }}, '{{ addslashes($asset->nama_barang) }}')"><i class="fas fa-trash"></i></button>
                                @endif
                                @endif
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

<!-- Delete Confirm Modal -->
<div class="modal-backdrop" id="deleteModal">
    <div class="modal confirm-modal">
        <div class="modal-body" style="padding:28px 24px;text-align:center;">
            <div class="icon"><i class="fas fa-trash"></i></div>
            <h3>Hapus Aset</h3>
            <p>Apakah Anda yakin ingin menghapus aset</p>
            <p><strong id="deleteItemName" class="strong"></strong></p>
            <p style="font-size:12px;color:#94a3b8;margin-top:6px;">Tindakan ini tidak dapat dibatalkan.</p>
            <div style="display:flex;gap:10px;justify-content:center;margin-top:20px;">
                <form id="deleteForm" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </form>
                <button class="btn btn-outline" onclick="closeModal('deleteModal')">Batal</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(id, name) {
    document.getElementById('deleteItemName').textContent = name;
    document.getElementById('deleteForm').action = '/assets/' + id;
    openModal('deleteModal');
}
</script>
@endpush
@endsection
