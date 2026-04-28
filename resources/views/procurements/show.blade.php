@extends('layouts.app')
@section('title', 'Detail Pengadaan')
@section('page-title', 'Pengadaan Aset')

@section('content')
<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;">
    <div>
        <h1>Detail Pengadaan</h1>
        <p>Kode: <strong>{{ $procurement->kode_pengadaan }}</strong></p>
    </div>
    <a href="{{ route('procurements.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div style="max-width:720px;">
    <!-- Status Banner -->
    <div class="card" style="margin-bottom:20px;">
        <div class="card-body" style="display:flex;align-items:center;gap:16px;">
            <div style="width:52px;height:52px;border-radius:12px;background:{{ $procurement->status === 'Disetujui' ? '#dcfce7' : ($procurement->status === 'Ditolak' ? '#fee2e2' : '#fef9c3') }};display:flex;align-items:center;justify-content:center;font-size:20px;color:{{ $procurement->status === 'Disetujui' ? '#16a34a' : ($procurement->status === 'Ditolak' ? '#dc2626' : '#a16207') }};">
                <i class="fas fa-{{ $procurement->status === 'Disetujui' ? 'circle-check' : ($procurement->status === 'Ditolak' ? 'circle-xmark' : 'clock') }}"></i>
            </div>
            <div style="flex:1;">
                <p style="font-size:13px;color:#64748b;margin-bottom:2px;">Status Pengadaan</p>
                <span class="badge badge-{{ $procurement->status === 'Disetujui' ? 'success' : ($procurement->status === 'Ditolak' ? 'danger' : 'warning') }}" style="font-size:14px;padding:5px 14px;">{{ $procurement->status }}</span>
            </div>
            @if($procurement->status === 'Pending' && (auth()->user()->isSuperAdmin() || auth()->user()->iskepalayayasan()))
            <div style="display:flex;gap:8px;">
                <button class="btn btn-success" onclick="openApprove({{ $procurement->id }}, '{{ addslashes($procurement->nama_barang) }}', 'Disetujui')">
                    <i class="fas fa-check"></i> Setujui
                </button>
                <button class="btn btn-danger" onclick="openApprove({{ $procurement->id }}, '{{ addslashes($procurement->nama_barang) }}', 'Ditolak')">
                    <i class="fas fa-times"></i> Tolak
                </button>
            </div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <p style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#2563eb;margin-bottom:16px;padding-bottom:8px;border-bottom:1.5px solid #eff6ff;">Detail Pengadaan</p>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div>
                    <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Nama Barang</p>
                    <p style="font-weight:600;font-size:15px;">{{ $procurement->nama_barang }}</p>
                </div>
                <div>
                    <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Kategori</p>
                    <p style="font-weight:500;">{{ $procurement->kategori }}</p>
                </div>
                <div>
                    <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Unit Kerja</p>
                    <p style="font-weight:500;">{{ $procurement->unit_kerja }}</p>
                </div>
                <div>
                    <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Jumlah</p>
                    <p style="font-weight:600;font-size:18px;">{{ $procurement->jumlah }} <span style="font-size:13px;font-weight:400;color:#64748b;">unit</span></p>
                </div>
                <div>
                    <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Estimasi Harga</p>
                    <p style="font-weight:700;font-size:16px;color:#2563eb;">Rp {{ number_format($procurement->estimasi_harga, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Sumber Dana</p>
                    <p style="font-weight:500;">{{ $procurement->sumber_dana }}</p>
                </div>
                <div>
                    <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Diajukan Oleh</p>
                    <p style="font-weight:500;">{{ $procurement->pengaju->name ?? '-' }}</p>
                </div>
                <div>
                    <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Tanggal Pengajuan</p>
                    <p style="font-weight:500;">{{ $procurement->tanggal_pengajuan->format('d M Y') }}</p>
                </div>
                @if($procurement->tanggal_approval)
                <div>
                    <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Diproses Oleh</p>
                    <p style="font-weight:500;">{{ $procurement->approver->name ?? '-' }}</p>
                </div>
                <div>
                    <p style="font-size:12px;color:#94a3b8;margin-bottom:4px;">Tanggal Approval</p>
                    <p style="font-weight:500;">{{ $procurement->tanggal_approval->format('d M Y') }}</p>
                </div>
                @endif
            </div>

            <div style="margin-top:16px;padding-top:16px;border-top:1px solid #f1f5f9;">
                <p style="font-size:12px;color:#94a3b8;margin-bottom:6px;font-weight:600;">Alasan Pengadaan</p>
                <p style="font-size:13.5px;color:#374151;background:#f8fafc;border-radius:8px;padding:12px 14px;border:1px solid #e2e8f0;line-height:1.6;">{{ $procurement->alasan_pengadaan }}</p>
            </div>

            @if($procurement->catatan_approval)
            <div style="margin-top:14px;">
                <p style="font-size:12px;color:#94a3b8;margin-bottom:6px;font-weight:600;">Catatan Approval</p>
                <p style="font-size:13.5px;color:#374151;background:{{ $procurement->status === 'Disetujui' ? '#f0fdf4' : '#fef2f2' }};border-radius:8px;padding:12px 14px;border:1px solid {{ $procurement->status === 'Disetujui' ? '#bbf7d0' : '#fecaca' }};line-height:1.6;">{{ $procurement->catatan_approval }}</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal-backdrop" id="approveModal">
    <div class="modal" style="max-width:440px;">
        <div class="modal-header">
            <h3 id="approveTitle">Proses Pengadaan</h3>
            <button class="modal-close" onclick="closeModal('approveModal')"><i class="fas fa-times"></i></button>
        </div>
        <form id="approveForm" method="POST">
            @csrf
            <input type="hidden" name="action" id="approveAction">
            <div class="modal-body">
                <p style="font-size:13.5px;color:#64748b;margin-bottom:14px;" id="approveDesc"></p>
                <div class="form-group">
                    <label class="form-label">Catatan (Opsional)</label>
                    <textarea name="catatan_approval" class="form-control" rows="3" placeholder="Tambahkan catatan..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('approveModal')">Batal</button>
                <button type="submit" class="btn btn-primary" id="approveSubmitBtn">Konfirmasi</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openApprove(id, name, action) {
    document.getElementById('approveForm').action = '/approvals/' + id + '/action';
    document.getElementById('approveAction').value = action;
    document.getElementById('approveTitle').textContent = (action === 'Disetujui' ? 'Setujui' : 'Tolak') + ' Pengadaan';
    document.getElementById('approveDesc').textContent = (action === 'Disetujui' ? 'Anda akan menyetujui: ' : 'Anda akan menolak: ') + name;
    document.getElementById('approveSubmitBtn').className = 'btn ' + (action === 'Disetujui' ? 'btn-success' : 'btn-danger');
    openModal('approveModal');
}
</script>
@endpush
@endsection
