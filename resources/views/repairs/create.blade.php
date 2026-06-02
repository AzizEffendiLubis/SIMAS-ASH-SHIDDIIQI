@extends('layouts.app')
@section('title', 'Laporkan Kerusakan')
@section('page-title', 'Perbaikan Aset')
@section('page-parent', 'Laporkan Kerusakan')

@section('content')

{{-- ── Page Header ── --}}
<div class="page-header-row">
    <div class="ph-left">
        <h1>Laporkan Kerusakan Aset</h1>
        <p>Isi formulir berikut untuk melaporkan kerusakan aset</p>
    </div>
    <div class="ph-right">
        <a href="{{ route('repairs.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

{{-- Error summary --}}
@if($errors->any())
<div class="alert alert-error">
    <i class="fas fa-triangle-exclamation"></i>
    <div>
        <p style="font-weight:700;margin-bottom:4px;">Terdapat kesalahan:</p>
        <ul style="margin:0;padding-left:16px;">
            @foreach($errors->all() as $error)
                <li style="font-size:13px;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<div class="card" style="max-width:740px;">
    <div class="card-body">
        <form action="{{ route('repairs.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{--
                asset_id: diisi JS jika nama yang diketik cocok dengan aset terdaftar.
                Kosong jika pengguna menulis nama yang tidak ada di daftar (mode manual).
                RepairController::store() membaca field ini untuk mengaitkan laporan ke aset.
            --}}
            <input type="hidden" name="asset_id"  id="inputAssetId"  value="{{ old('asset_id') }}">
            <input type="hidden" name="mode"       id="inputMode"     value="{{ old('mode', 'manual') }}">

            {{-- ════════════════════════════════════════
                 SECTION 1 — Barang yang Rusak
            ════════════════════════════════════════ --}}
            <div class="form-section">
                <p class="form-section-title">
                    <i class="fas fa-box" style="margin-right:5px;"></i>Barang yang Rusak
                </p>

                {{--
                    Input tunggal dengan autocomplete dari daftar aset.
                    Pengguna bisa:
                    1. Mengetik → muncul saran dari $assets → pilih → asset_id terisi otomatis
                    2. Mengetik bebas tanpa pilih dari saran → tetap valid, asset_id kosong
                    Dokumen: "Pelaporan lebih fleksibel dan universal."
                --}}
                <div class="form-group" style="position:relative;">
                    <label class="form-label">
                        Nama Barang yang Rusak <span class="required">*</span>
                    </label>
                    <div class="input-wrap">
                        <i class="input-icon fas fa-box-open"></i>
                        <input type="text"
                            name="nama_aset_laporan"
                            id="inputNamaAset"
                            class="form-control @error('nama_aset_laporan') is-invalid @enderror"
                            placeholder="Ketik nama barang atau pilih dari daftar aset..."
                            value="{{ old('nama_aset_laporan') }}"
                            autocomplete="off"
                            autofocus>
                    </div>
                    <p class="form-hint" id="namaHint">
                        Tulis nama barang sedetail mungkin. Saran akan muncul jika barang terdaftar
                        @if(auth()->user()->unit_id && !auth()->user()->isAdminUtama() && !auth()->user()->isKepalaYayasan())
                            di unit <strong>{{ auth()->user()->unit->nama_unit }}</strong>
                        @endif
                    </p>
                    @error('nama_aset_laporan') <p class="invalid-feedback">{{ $message }}</p> @enderror

                    {{-- Dropdown saran autocomplete --}}
                    <div id="assetSuggestions" style="
                        display:none;
                        position:absolute;
                        top:100%; left:0; right:0;
                        background:#fff;
                        border:1.5px solid var(--primary);
                        border-top:none;
                        border-radius:0 0 var(--radius-sm) var(--radius-sm);
                        box-shadow:var(--shadow);
                        z-index:50;
                        max-height:220px;
                        overflow-y:auto;">
                    </div>
                </div>

                {{-- Preview: muncul jika pengguna memilih dari saran --}}
                <div id="assetPreview" style="display:none;
                    background:var(--primary-xlight);border:1px solid var(--primary-light);
                    border-radius:var(--radius-sm);padding:11px 14px;
                    margin-top:-10px;margin-bottom:16px;
                    display:none;align-items:center;gap:10px;">
                    <i class="fas fa-circle-check" style="color:var(--primary);font-size:16px;flex-shrink:0;"></i>
                    <div style="min-width:0;">
                        <p style="font-size:12px;color:var(--primary);font-weight:700;">Aset terdaftar dipilih</p>
                        <p id="previewDetail" style="font-size:12.5px;color:var(--gray-600);margin-top:1px;"></p>
                    </div>
                    <button type="button" onclick="clearAsset()"
                        style="margin-left:auto;background:none;border:none;cursor:pointer;
                               color:var(--gray-400);font-size:13px;flex-shrink:0;"
                        title="Hapus pilihan, tulis manual">
                        <i class="fas fa-xmark"></i>
                    </button>
                </div>

                {{-- Lokasi --}}
                <div class="form-group">
                    <label class="form-label">Lokasi Kerusakan</label>
                    <div class="input-wrap">
                        <i class="input-icon fas fa-location-dot"></i>
                        <input type="text" name="lokasi_kerusakan" id="inputLokasi"
                            class="form-control @error('lokasi_kerusakan') is-invalid @enderror"
                            placeholder="Contoh: Ruang Kelas 7A, Lab Komputer Lantai 2"
                            value="{{ old('lokasi_kerusakan') }}">
                    </div>
                    @error('lokasi_kerusakan') <p class="invalid-feedback">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- ════════════════════════════════════════
                 SECTION 2 — Detail Kerusakan
            ════════════════════════════════════════ --}}
            <div class="form-section">
                <p class="form-section-title">
                    <i class="fas fa-triangle-exclamation" style="margin-right:5px;"></i>Detail Kerusakan
                </p>

                <div class="form-group">
                    <label class="form-label">
                        Deskripsi Kerusakan <span class="required">*</span>
                    </label>
                    <textarea name="deskripsi_kerusakan" rows="4"
                        class="form-control @error('deskripsi_kerusakan') is-invalid @enderror"
                        placeholder="Jelaskan kerusakan: gejala, kapan terjadi, kondisi saat ini...">{{ old('deskripsi_kerusakan') }}</textarea>
                    @error('deskripsi_kerusakan') <p class="invalid-feedback">{{ $message }}</p> @enderror
                </div>

                {{--
                    Multi-foto kerusakan: name="fotos[]"
                    Dokumen: "Upload foto kerusakan dapat dilakukan lebih dari satu gambar."
                    Petugas tidak ditampilkan — penugasan Admin Utama via edit().
                --}}
                <div class="form-group">
                    <label class="form-label">
                        Foto Kerusakan <span class="required">*</span>
                    </label>
                    <input type="file" name="fotos[]"
                        class="form-control @error('fotos') is-invalid @enderror @error('fotos.*') is-invalid @enderror"
                        accept=".jpg,.jpeg,.png,.webp"
                        multiple
                        required>
                    <p class="form-hint">
                        <i class="fas fa-circle-info" style="color:var(--gray-300);margin-right:3px;"></i>
                        Wajib diisi &middot; Format JPG / PNG / WEBP &middot; Maks. 2 MB per foto &middot; Hingga 5 foto &middot; Wajib minimal 1 foto
                    </p>
                    @error('fotos')   <p class="invalid-feedback">{{ $message }}</p> @enderror
                    @error('fotos.*') <p class="invalid-feedback">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- ── Footer Aksi ── --}}
            <div style="display:flex;gap:10px;justify-content:flex-end;padding-top:4px;">
                <a href="{{ route('repairs.index') }}" class="btn btn-outline">
                    <i class="fas fa-xmark"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Kirim Laporan
                </button>
            </div>

        </form>
    </div>
</div>

@push('scripts')
@php
// Siapkan data di PHP agar tidak konflik dengan Blade parser
// (fn() => [...] di dalam @json() menyebabkan 'Unclosed [' parse error)
$assetsForJs = $assets->map(function ($a) {
    return [
        'id'     => $a->id,
        'nama'   => $a->nama_barang,
        'kode'   => $a->kode_aset,
        'lokasi' => $a->lokasi_barang ?? '',
    ];
})->values()->toArray();
@endphp
<script>
// Data aset dari controller, difilter per unit di RepairController::create()
const ASSETS = @json($assetsForJs);

// ── Elemen ───────────────────────────────────────────────────────────────
const inputNama   = document.getElementById('inputNamaAset');
const inputAsset  = document.getElementById('inputAssetId');
const inputMode   = document.getElementById('inputMode');
const inputLokasi = document.getElementById('inputLokasi');
const suggestions = document.getElementById('assetSuggestions');
const preview     = document.getElementById('assetPreview');
const previewDetail = document.getElementById('previewDetail');

// ── Tampilkan saran sesuai teks yang diketik ─────────────────────────────
inputNama.addEventListener('input', function () {
    const q = this.value.trim().toLowerCase();

    // Reset asset_id dan mode jika pengguna mengubah teks setelah pilih
    inputAsset.value = '';
    inputMode.value  = 'manual';
    preview.style.display = 'none';

    if (q.length < 1) { hideSuggestions(); return; }

    const matches = ASSETS.filter(a =>
        a.nama.toLowerCase().includes(q) ||
        a.kode.toLowerCase().includes(q)
    ).slice(0, 8); // max 8 saran

    if (!matches.length) { hideSuggestions(); return; }

    suggestions.innerHTML = matches.map(a => `
        <div class="suggestion-item"
             data-id="{{ '' }}"
             data-real-id="${a.id}"
             data-nama="${escHtml(a.nama)}"
             data-kode="${escHtml(a.kode)}"
             data-lokasi="${escHtml(a.lokasi)}"
             onclick="selectAsset(this)"
             style="padding:10px 14px;cursor:pointer;font-size:13.5px;
                    border-bottom:1px solid var(--gray-100);">
            <span style="font-weight:600;color:var(--gray-800);">${escHtml(a.nama)}</span>
            <span style="font-size:12px;color:var(--gray-400);margin-left:6px;">${escHtml(a.kode)}</span>
            ${a.lokasi ? `<br><span style="font-size:12px;color:var(--gray-400);">
                <i class="fas fa-location-dot" style="font-size:10px;"></i> ${escHtml(a.lokasi)}
            </span>` : ''}
        </div>
    `).join('');

    suggestions.style.display = 'block';
});

// ── Pilih aset dari saran ─────────────────────────────────────────────────
function selectAsset(el) {
    const id     = el.getAttribute('data-real-id');
    const nama   = el.getAttribute('data-nama');
    const kode   = el.getAttribute('data-kode');
    const lokasi = el.getAttribute('data-lokasi');

    inputNama.value  = nama;
    inputAsset.value = id;
    inputMode.value  = 'dropdown';

    // Auto-isi lokasi jika masih kosong
    if (!inputLokasi.value && lokasi) {
        inputLokasi.value = lokasi;
    }

    // Tampilkan preview
    previewDetail.textContent = kode + (lokasi ? ' · ' + lokasi : '');
    preview.style.display = 'flex';

    hideSuggestions();
    inputNama.focus();
}

// ── Hapus pilihan aset (kembali ke tulis manual) ──────────────────────────
function clearAsset() {
    inputAsset.value = '';
    inputMode.value  = 'manual';
    inputNama.value  = '';
    inputLokasi.value = '';
    preview.style.display = 'none';
    inputNama.focus();
}

// ── Sembunyikan saran ─────────────────────────────────────────────────────
function hideSuggestions() {
    suggestions.style.display = 'none';
    suggestions.innerHTML = '';
}

// Tutup saran jika klik di luar
document.addEventListener('click', function (e) {
    if (!inputNama.contains(e.target) && !suggestions.contains(e.target)) {
        hideSuggestions();
    }
});

// Navigasi keyboard: Escape menutup saran
inputNama.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') hideSuggestions();
});

// Hover styling saran
document.addEventListener('mouseover', function (e) {
    const item = e.target.closest('.suggestion-item');
    if (item && suggestions.contains(item)) {
        suggestions.querySelectorAll('.suggestion-item').forEach(el =>
            el.style.background = '');
        item.style.background = 'var(--primary-xlight)';
    }
});
document.addEventListener('mouseout', function (e) {
    const item = e.target.closest('.suggestion-item');
    if (item && suggestions.contains(item)) {
        item.style.background = '';
    }
});

// Helper escape HTML
function escHtml(str) {
    return String(str)
        .replace(/&/g,'&amp;')
        .replace(/</g,'&lt;')
        .replace(/>/g,'&gt;')
        .replace(/"/g,'&quot;');
}

// ── Init: pulihkan state setelah validasi gagal (old values) ─────────────
document.addEventListener('DOMContentLoaded', function () {
    const oldAssetId = '{{ old('asset_id') }}';
    const oldNama    = '{{ old('nama_aset_laporan') }}';

    if (oldAssetId && oldNama) {
        // Cari data aset dari array
        const found = ASSETS.find(a => String(a.id) === String(oldAssetId));
        if (found) {
            inputAsset.value = found.id;
            inputMode.value  = 'dropdown';
            previewDetail.textContent = found.kode + (found.lokasi ? ' · ' + found.lokasi : '');
            preview.style.display = 'flex';
        }
    }
});
</script>
@endpush

@endsection