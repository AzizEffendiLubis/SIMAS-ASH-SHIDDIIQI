@extends('layouts.app')
@section('title', 'Pengadaan Aset')
@section('page-title', 'Pengadaan Aset')

@section('content')
<div class="page-header-row">
    <div class="page-header">
        <h1>Pengadaan Aset</h1>
        <p>Daftar pengajuan pengadaan aset
            @if(auth()->user()->isAdminUnit()) unit <strong>{{ auth()->user()->unit_kerja }}</strong> @endif
        </p>
    </div>
    <a href="{{ route('procurements.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Ajukan Pengadaan
    </a>
</div>

{{-- Filter --}}
<div class="card" style="margin-bottom:16px;">
    <div class="card-body" style="padding:14px 18px;">
        <form method="GET" class="filter-row">
            <div class="search-wrap" style="flex:1;min-width:180px;">
                <i class="fas fa-search"></i>
                <input type="text" name="search" class="form-control"
                    placeholder="Cari nama barang atau kode..."
                    value="{{ request('search') }}">
            </div>
            <select name="status" class="form-control" style="width:160px;">
                <option value="">Semua Status</option>
                <option value="Pending"   {{ request('status')=='Pending'   ?'selected':'' }}>Pending</option>
                <option value="Disetujui" {{ request('status')=='Disetujui' ?'selected':'' }}>Disetujui</option>
                <option value="Ditolak"   {{ request('status')=='Ditolak'   ?'selected':'' }}>Ditolak</option>
            </select>
            <button type="submit" class="btn btn-primary" style="height:42px;">
                <i class="fas fa-filter"></i> Filter
            </button>
            @if(request()->hasAny(['search','status']))
            <a href="{{ route('procurements.index') }}" class="btn btn-outline" style="height:42px;">Reset</a>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding:0;">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Unit</th>
                        <th style="text-align:center;">Jml</th>
                        <th>Est. Harga</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th style="width:90px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($procurements as $proc)
                    <tr>
                        <td>
                            <code style="font-size:12px;background:#f1f5f9;padding:2px 7px;border-radius:5px;">
                                {{ $proc->kode_pengadaan }}
                            </code>
                        </td>
                        <td><div style="font-weight:600;">{{ $proc->nama_barang }}</div></td>
                        <td style="font-size:13px;">{{ $proc->kategori }}</td>
                        <td style="font-size:13px;">{{ $proc->unit_kerja }}</td>
                        <td style="text-align:center;font-weight:600;">{{ $proc->jumlah }}</td>
                        <td style="font-size:13px;font-weight:600;white-space:nowrap;">
                            Rp {{ number_format($proc->estimasi_harga, 0, ',', '.') }}
                        </td>
                        <td style="font-size:12.5px;color:#64748b;white-space:nowrap;">
                            {{ $proc->tanggal_pengajuan->format('d/m/Y') }}
                        </td>
                        <td>
                            <span class="badge badge-{{ $proc->status === 'Disetujui' ? 'success' : ($proc->status === 'Ditolak' ? 'danger' : 'warning') }}">
                                {{ $proc->status }}
                            </span>
                            @if($proc->status === 'Ditolak' && $proc->catatan_approval)
                            <div style="font-size:11px;color:#dc2626;margin-top:2px;" title="{{ $proc->catatan_approval }}">
                                <i class="fas fa-circle-info"></i> Ada catatan
                            </div>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex;gap:5px;">
                                <a href="{{ route('procurements.show', $proc) }}"
                                   class="btn btn-outline btn-sm btn-icon" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($proc->status === 'Pending')
                                <button class="btn btn-outline btn-sm btn-icon" style="color:#dc2626;"
                                    title="Batalkan"
                                    onclick="confirmDelete({{ $proc->id }}, '{{ addslashes($proc->nama_barang) }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="text-align:center;padding:40px;color:#94a3b8;">
                            <i class="fas fa-cart-plus" style="font-size:32px;display:block;margin-bottom:10px;opacity:.3;"></i>
                            Belum ada pengajuan pengadaan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($procurements->hasPages())
        <div style="padding:14px 20px;border-top:1px solid #f1f5f9;">
            {{ $procurements->links('vendor.pagination.simple') }}
        </div>
        @endif
    </div>
</div>

{{-- Delete Confirm --}}
<div class="modal-backdrop" id="deleteModal">
    <div class="modal confirm-modal">
        <div class="modal-body" style="padding:28px 24px;text-align:center;">
            <div class="confirm-icon" style="background:#fee2e2;color:#dc2626;">
                <i class="fas fa-trash"></i>
            </div>
            <h3>Batalkan Pengadaan</h3>
            <p style="color:#64748b;font-size:13.5px;margin-top:6px;">
                Batalkan pengajuan <strong id="deleteItemName"></strong>?
            </p>
            <p style="font-size:12px;color:#94a3b8;margin-top:4px;">Tindakan ini tidak dapat dibatalkan.</p>
            <div style="display:flex;gap:10px;justify-content:center;margin-top:20px;">
                <form id="deleteForm" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">Ya, Batalkan</button>
                </form>
                <button class="btn btn-outline" onclick="closeModal('deleteModal')">Kembali</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(id, name) {
    document.getElementById('deleteItemName').textContent = name;
    document.getElementById('deleteForm').action = '/procurements/' + id;
    openModal('deleteModal');
}
</script>
@endpush
@endsection
