@extends('layouts.app')
@section('title', 'Master Data')
@section('page-title', 'Master Data')

@push('styles')
<style>
    /* ── MD Overlay (slide-in drawer dari kanan) ── */
    .md-overlay {
        display: none; position: fixed;
        inset: 0; z-index: 300;
        background: rgba(15,23,42,.45);
        align-items: center; justify-content: flex-end;
        animation: mdFadeIn .2s ease;
    }
    .md-overlay.open { display: flex; }
    @keyframes mdFadeIn { from { opacity:0 } to { opacity:1 } }

    .md-box {
        background: #fff;
        width: 100%; max-width: 420px; height: 100vh;
        display: flex; flex-direction: column;
        box-shadow: -8px 0 32px rgba(0,0,0,.12);
        animation: mdSlideIn .25s cubic-bezier(.4,0,.2,1);
        overflow-y: auto;
    }
    @keyframes mdSlideIn {
        from { transform: translateX(40px); opacity:0 }
        to   { transform: translateX(0);    opacity:1 }
    }
    .md-header {
        padding: 18px 20px 14px;
        display: flex; justify-content: space-between; align-items: center;
        border-bottom: 1px solid var(--gray-100); flex-shrink: 0;
    }
    .md-title {
        font-size: 15px; font-weight: 700; color: var(--gray-800);
        display: flex; align-items: center; gap: 8px; margin: 0;
    }
    .md-title i { color: var(--primary); }
    .md-close {
        width: 30px; height: 30px; border: none;
        background: var(--gray-100); border-radius: 7px;
        display: flex; align-items: center; justify-content: center;
        font-size: 14px; color: var(--gray-500); cursor: pointer;
        transition: background var(--transition); flex-shrink: 0;
    }
    .md-close:hover { background: var(--gray-200); }
    .md-body   { padding: 18px 20px; flex: 1; overflow-y: auto; }
    .md-footer {
        padding: 14px 20px;
        border-top: 1px solid var(--gray-100);
        display: flex; justify-content: flex-end; gap: 8px; flex-shrink: 0;
    }

    /* ── Checkbox row ── */
    .check-row {
        display: flex; align-items: center; gap: 9px;
        cursor: pointer; font-size: 13px; font-weight: 600;
        color: var(--gray-700); margin-bottom: 4px;
    }
    .check-row input[type="checkbox"] {
        width: 16px; height: 16px; accent-color: var(--primary); cursor: pointer;
    }

    /* ── Kode badge ── */
    .kode-badge {
        font-size: 12px; background: var(--gray-50);
        border: 1px solid var(--gray-200); border-radius: 5px;
        padding: 2px 8px; font-family: monospace; letter-spacing: .5px;
    }

    /* ── Satuan chips ── */
    .satuan-chips { display: flex; flex-wrap: wrap; gap: 8px; }
    .satuan-chip {
        background: var(--gray-50); border: 1px solid var(--gray-200);
        border-radius: 8px; padding: 7px 14px;
        font-size: 13.5px; font-weight: 500; color: var(--gray-700);
    }

    /* ── Section divider dalam satu halaman ── */
    .md-section { margin-bottom: 24px; }
    .md-section:last-child { margin-bottom: 0; }

    @media (max-width: 640px) {
        .md-box { max-width: 100%; }
    }
</style>
@endpush

@section('content')

{{-- ── Page Header ── --}}
<div class="page-header-row">
    <div class="ph-left">
        <h1>Master Data</h1>
        <p>Data referensi sistem: unit kerja, sumber dana, dan satuan aset</p>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     SECTION 1 — UNIT KERJA
     Tidak bisa tambah dari sini. Edit tetap tersedia.
     kode_unit tidak dapat diubah setelah disimpan.
     Nonaktifkan via is_active bukan delete.
══════════════════════════════════════════════════════════════ --}}
<div class="md-section">
    <div class="card">
        <div class="card-header">
            <div style="display:flex;align-items:center;gap:8px;">
                <i class="fas fa-building" style="color:var(--primary);font-size:14px;"></i>
                <h2>Unit Kerja</h2>
                <span class="badge badge-info">{{ $units->count() }}</span>
            </div>
        </div>
        <div class="card-body" style="padding:0;">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th style="width:130px;">Kode Unit</th>
                            <th>Nama Unit</th>
                            <th>Deskripsi</th>
                            <th style="width:100px;">Tipe</th>
                            <th style="width:90px;">Status</th>
                            <th style="width:52px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($units as $unit)
                        <tr>
                            <td>
                                {{-- kode_unit: dikunci, bagian dari kode_aset --}}
                                <span class="kode-badge">{{ $unit->kode_unit }}</span>
                            </td>
                            <td style="font-weight:600;font-size:13.5px;color:var(--gray-800);">
                                {{ $unit->nama_unit }}
                            </td>
                            <td style="font-size:13px;color:var(--gray-500);">
                                {{ $unit->deskripsi ?? '—' }}
                            </td>
                            <td>
                                {{-- is_yayasan: boolean cast di Unit model --}}
                                @if($unit->is_yayasan)
                                    <span class="badge badge-warning">Yayasan</span>
                                @else
                                    <span class="badge badge-secondary">Unit</span>
                                @endif
                            </td>
                            <td>
                                @if($unit->is_active)
                                    <span class="badge badge-aktif">Aktif</span>
                                @else
                                    <span class="badge badge-nonaktif">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                {{--
                                    kode_unit TIDAK dikirim ke modal edit.
                                    updateUnit() tidak menerima perubahan kode_unit.
                                --}}
                                <button type="button"
                                    class="btn btn-outline btn-sm btn-icon"
                                    title="Edit Unit"
                                    onclick="mdOpenEditUnit(
                                        {{ $unit->id }},
                                        '{{ e(addslashes($unit->nama_unit)) }}',
                                        '{{ e(addslashes($unit->deskripsi ?? '')) }}',
                                        {{ $unit->is_active ? 'true' : 'false' }}
                                    )">
                                    <i class="fas fa-pen"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                <i class="fas fa-building"></i>
                                <p>Belum ada unit terdaftar</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     SECTION 2 — SUMBER DANA
     Tidak bisa tambah dari sini. Edit tetap tersedia.
     Dokumen: "Daftar sumber dana bersifat dinamis."
     (dinamis di DB, tapi penambahan hanya via seeder/admin teknis)
══════════════════════════════════════════════════════════════ --}}
<div class="md-section">
    <div class="card">
        <div class="card-header">
            <div style="display:flex;align-items:center;gap:8px;">
                <i class="fas fa-money-bill-wave" style="color:var(--primary);font-size:14px;"></i>
                <h2>Sumber Dana</h2>
                <span class="badge badge-info">{{ $fundingSources->count() }}</span>
            </div>
        </div>
        <div class="card-body" style="padding:0;">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Sumber Dana</th>
                            <th>Deskripsi</th>
                            <th style="width:90px;">Status</th>
                            <th style="width:52px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fundingSources as $fs)
                        <tr>
                            <td style="font-weight:600;font-size:13.5px;color:var(--gray-800);">
                                {{ $fs->nama_sumber }}
                            </td>
                            <td style="font-size:13px;color:var(--gray-500);">
                                {{ $fs->deskripsi ?? '—' }}
                            </td>
                            <td>
                                @if($fs->is_active)
                                    <span class="badge badge-aktif">Aktif</span>
                                @else
                                    <span class="badge badge-nonaktif">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <button type="button"
                                    class="btn btn-outline btn-sm btn-icon"
                                    title="Edit Sumber Dana"
                                    onclick="mdOpenEditDana(
                                        {{ $fs->id }},
                                        '{{ e(addslashes($fs->nama_sumber)) }}',
                                        '{{ e(addslashes($fs->deskripsi ?? '')) }}',
                                        {{ $fs->is_active ? 'true' : 'false' }}
                                    )">
                                    <i class="fas fa-pen"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="empty-state">
                                <i class="fas fa-money-bill-wave"></i>
                                <p>Belum ada sumber dana terdaftar</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     SECTION 3 — SATUAN ASET (read-only)
     Dokumen: "Daftar satuan aset bersifat tetap."
     Model UnitSatuan → tabel units_satuan, diisi via seeder.
     Tidak ada CRUD sama sekali.
══════════════════════════════════════════════════════════════ --}}
<div class="md-section">
    <div class="card">
        <div class="card-header">
            <div style="display:flex;align-items:center;gap:8px;">
                <i class="fas fa-ruler" style="color:var(--primary);font-size:14px;"></i>
                <h2>Satuan Aset</h2>
                <span class="badge badge-info">{{ $satuanList->count() }}</span>
            </div>
            <span style="display:flex;align-items:center;gap:5px;font-size:12px;color:var(--gray-400);">
                <i class="fas fa-lock" style="font-size:11px;"></i> Read-only
            </span>
        </div>
        <div class="card-body">
            <p style="font-size:13px;color:var(--gray-400);margin-bottom:14px;">
                Daftar satuan bersifat tetap dan dikelola langsung oleh sistem.
            </p>
            <div class="satuan-chips">
                @forelse($satuanList as $satuan)
                    <span class="satuan-chip">{{ $satuan->nama_satuan }}</span>
                @empty
                    <p style="font-size:13px;color:var(--gray-300);">Belum ada satuan terdaftar.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>


{{-- ══════════════════════════════════════════════════════════════
     MD-OVERLAY — Drawer kanan untuk form edit saja
══════════════════════════════════════════════════════════════ --}}
<div id="md-overlay" class="md-overlay" onclick="mdHandleOverlayClick(event)">

    {{-- ── EDIT UNIT ── --}}
    <div id="md-edit-unit" class="md-box" style="display:none;">
        <div class="md-header">
            <p class="md-title"><i class="fas fa-pen"></i> Edit Unit Kerja</p>
            <button type="button" class="md-close" onclick="mdCloseModal()">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        {{--
            Action diisi JS: PUT /masterdata/units/{id} → masterdata.units.update
            kode_unit TIDAK ada di form — tidak bisa diubah setelah disimpan.
        --}}
        <form method="POST" id="md-form-edit-unit" action="">
            @csrf @method('PUT')
            <div class="md-body">
                <div class="form-group">
                    <label class="form-label">Nama Unit <span style="color:var(--danger);">*</span></label>
                    <input type="text" name="nama_unit" id="md-edit-unit-nama"
                        class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" id="md-edit-unit-deskripsi"
                        class="form-control" rows="2"></textarea>
                </div>
                <div class="form-group">
                    <input type="hidden" name="is_active" value="0">
                    <label class="check-row">
                        <input type="checkbox" name="is_active" id="md-edit-unit-aktif" value="1">
                        Unit Aktif
                    </label>
                    <p class="form-hint">Menonaktifkan unit tidak menghapus aset yang sudah ada.</p>
                </div>
            </div>
            <div class="md-footer">
                <button type="button" class="btn btn-outline" onclick="mdCloseModal()">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-floppy-disk"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    {{-- ── EDIT SUMBER DANA ── --}}
    <div id="md-edit-dana" class="md-box" style="display:none;">
        <div class="md-header">
            <p class="md-title"><i class="fas fa-pen"></i> Edit Sumber Dana</p>
            <button type="button" class="md-close" onclick="mdCloseModal()">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        {{-- PUT /masterdata/funding-sources/{id} → masterdata.funding.update --}}
        <form method="POST" id="md-form-edit-dana" action="">
            @csrf @method('PUT')
            <div class="md-body">
                <div class="form-group">
                    <label class="form-label">Nama Sumber Dana <span style="color:var(--danger);">*</span></label>
                    <input type="text" name="nama_sumber" id="md-edit-dana-nama"
                        class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" id="md-edit-dana-deskripsi"
                        class="form-control" rows="2"></textarea>
                </div>
                <div class="form-group">
                    <input type="hidden" name="is_active" value="0">
                    <label class="check-row">
                        <input type="checkbox" name="is_active" id="md-edit-dana-aktif" value="1">
                        Sumber Dana Aktif
                    </label>
                </div>
            </div>
            <div class="md-footer">
                <button type="button" class="btn btn-outline" onclick="mdCloseModal()">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-floppy-disk"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>{{-- /md-overlay --}}

@endsection

@push('scripts')
<script>
// ── Buka/tutup drawer ────────────────────────────────────────
function mdOpenModal(boxId) {
    document.querySelectorAll('.md-box').forEach(b => b.style.display = 'none');
    const box = document.getElementById(boxId);
    if (!box) return;
    box.style.display = 'flex';
    box.style.flexDirection = 'column';
    document.getElementById('md-overlay').classList.add('open');
    document.body.style.overflow = 'hidden';
    const first = box.querySelector('input:not([type="hidden"]), textarea');
    if (first) setTimeout(() => first.focus(), 120);
}

function mdCloseModal() {
    document.getElementById('md-overlay').classList.remove('open');
    document.querySelectorAll('.md-box').forEach(b => b.style.display = 'none');
    document.body.style.overflow = '';
}

function mdHandleOverlayClick(e) {
    if (e.target === document.getElementById('md-overlay')) mdCloseModal();
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') mdCloseModal();
});

// ── Populate form edit unit ──────────────────────────────────
// PUT /masterdata/units/{id} → masterdata.units.update
function mdOpenEditUnit(id, nama, deskripsi, isActive) {
    document.getElementById('md-form-edit-unit').action =
        '{{ route("masterdata.units.update", ":id") }}'.replace(':id', id);
    document.getElementById('md-edit-unit-nama').value      = nama;
    document.getElementById('md-edit-unit-deskripsi').value = deskripsi;
    document.getElementById('md-edit-unit-aktif').checked   = isActive;
    mdOpenModal('md-edit-unit');
}

// ── Populate form edit sumber dana ──────────────────────────
// PUT /masterdata/funding-sources/{id} → masterdata.funding.update
function mdOpenEditDana(id, nama, deskripsi, isActive) {
    document.getElementById('md-form-edit-dana').action =
        '{{ route("masterdata.funding.update", ":id") }}'.replace(':id', id);
    document.getElementById('md-edit-dana-nama').value      = nama;
    document.getElementById('md-edit-dana-deskripsi').value = deskripsi;
    document.getElementById('md-edit-dana-aktif').checked   = isActive;
    mdOpenModal('md-edit-dana');
}
</script>
@endpush