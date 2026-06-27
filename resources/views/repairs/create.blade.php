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
        <p class="error-summary-title">Terdapat kesalahan:</p>
        <ul class="error-summary-list">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<div class="card repair-form-card">
    <div class="card-body">
        <form action="{{ route('repairs.store') }}" method="POST" enctype="multipart/form-data" id="repairForm">
            @csrf

            {{--
                asset_id: WAJIB diisi — hanya bisa diisi via pilih dari dropdown saran.
                Jika kosong saat submit, form ditolak oleh JS sebelum dikirim ke server.
            --}}
            <input type="hidden" name="asset_id"          id="inputAssetId"   value="{{ old('asset_id') }}">
            <input type="hidden" name="nama_aset_laporan" id="inputNamaHidden" value="{{ old('nama_aset_laporan') }}">

            {{-- SECTION 1 — Barang yang Rusak --}}
            <div class="form-section">
                <p class="form-section-title">
                    <i class="fas fa-box"></i> Barang yang Rusak
                </p>

                {{--
                    Input autocomplete — pengguna WAJIB memilih dari saran.
                    Mengetik bebas lalu langsung submit tidak diizinkan.
                    Filter saran: starts-with (nama/kode diawali huruf yang diketik).
                --}}
                <div class="form-group asset-autocomplete">
                    <label class="form-label" for="inputNamaAset">
                        Nama Barang yang Rusak <span class="required">*</span>
                    </label>

                    {{-- Wrapper input + ikon status --}}
                    <div class="input-wrap">
                        <i class="input-icon fas fa-box-open"></i>
                        <input type="text"
                            id="inputNamaAset"
                            class="form-control @error('nama_aset_laporan') is-invalid @enderror @error('asset_id') is-invalid @enderror"
                            placeholder="Ketik nama atau kode aset..."
                            value="{{ old('nama_aset_laporan') }}"
                            autocomplete="off"
                            autofocus
                            aria-autocomplete="list"
                            aria-controls="assetSuggestions"
                            aria-expanded="false">
                        {{-- Indikator: centang (terpilih) / x (belum pilih) --}}
                        <span id="statusIcon" class="asset-status-icon"></span>
                    </div>

                    <p class="form-hint" id="namaHint">
                        <i class="fas fa-circle-info"></i>
                        Ketik minimal 1 huruf — aset yang berawalan huruf tersebut akan muncul. Wajib pilih dari daftar.
                        @if(auth()->user()->unit_id && !auth()->user()->isAdminUtama() && !auth()->user()->isKepalaYayasan())
                            &nbsp;Menampilkan aset unit <strong>{{ auth()->user()->unit->nama_unit }}</strong>.
                        @endif
                    </p>

                    {{-- Pesan error untuk asset_id (wajib pilih dari dropdown) --}}
                    <p id="dropdownError" class="dropdown-error">
                        <i class="fas fa-circle-exclamation"></i>
                        Pilih barang dari daftar saran — tidak bisa diisi manual.
                    </p>
                    @error('nama_aset_laporan') <p class="invalid-feedback">{{ $message }}</p> @enderror
                    @error('asset_id')          <p class="invalid-feedback">{{ $message }}</p> @enderror

                    {{-- Dropdown saran --}}
                    <div id="assetSuggestions" class="asset-suggestions" role="listbox"></div>
                </div>

                {{-- Preview: muncul setelah pilih dari saran --}}
                <div id="assetPreview" class="asset-preview">
                    <i class="fas fa-circle-check"></i>
                    <div class="asset-preview-body">
                        <p class="asset-preview-title">Aset terdaftar dipilih</p>
                        <p id="previewDetail" class="asset-preview-detail"></p>
                    </div>
                    <button type="button" class="asset-preview-change" onclick="clearAsset()" title="Ganti pilihan">
                        <i class="fas fa-pen-to-square"></i> Ganti
                    </button>
                </div>

                {{-- Lokasi --}}
                <div class="form-group">
                    <label class="form-label" for="inputLokasi">Lokasi Kerusakan</label>
                    <div class="input-wrap">
                        <i class="input-icon fas fa-location-dot"></i>
                        <input type="text" name="lokasi_kerusakan" id="inputLokasi"
                            class="form-control locked-input @error('lokasi_kerusakan') is-invalid @enderror"
                            placeholder="Pilih barang terlebih dahulu..."
                            value="{{ old('lokasi_kerusakan') }}"
                            readonly>
                    </div>
                    <p class="form-hint" id="lokasiHint" style="display:none;">
                        <i class="fas fa-lock"></i>
                        Lokasi diambil otomatis dari data aset dan tidak dapat diubah.
                    </p>
                    @error('lokasi_kerusakan') <p class="invalid-feedback">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- SECTION 2 — Detail Kerusakan --}}
            <div class="form-section">
                <p class="form-section-title">
                    <i class="fas fa-triangle-exclamation"></i> Detail Kerusakan
                </p>

                <div class="form-group">
                    <label class="form-label" for="deskripsiKerusakan">
                        Deskripsi Kerusakan <span class="required">*</span>
                    </label>
                    <textarea name="deskripsi_kerusakan" id="deskripsiKerusakan" rows="4"
                        class="form-control @error('deskripsi_kerusakan') is-invalid @enderror"
                        placeholder="Jelaskan kerusakan: gejala, kapan terjadi, kondisi saat ini...">{{ old('deskripsi_kerusakan') }}</textarea>
                    @error('deskripsi_kerusakan') <p class="invalid-feedback">{{ $message }}</p> @enderror
                </div>

                <div class="form-group" id="foto-group">
                    <label class="form-label">
                        Foto Kerusakan <span class="required">*</span>
                    </label>

                    {{-- Grid preview --}}
                    <div id="foto-preview-grid" class="foto-preview-grid"></div>
                    <p id="foto-counter" class="foto-counter"></p>

                    {{-- Dropzone --}}
                    <div id="foto-dropzone" class="foto-dropzone" onclick="document.getElementById('foto-picker').click()">
                        <i class="fas fa-camera"></i>
                        <p>Klik untuk pilih foto kerusakan</p>
                        <span>JPG / PNG / WEBP · maks. 2 MB · hingga 5 foto · wajib minimal 1</span>
                    </div>

                    <input type="file" id="foto-picker"
                        accept="image/jpg,image/jpeg,image/png,image/webp"
                        style="display:none">

                    {{-- Hidden inputs yang dikirim ke controller --}}
                    <div id="foto-hidden-inputs"></div>

                    @error('fotos')   <p class="invalid-feedback" style="display:block;">{{ $message }}</p> @enderror
                    @error('fotos.*') <p class="invalid-feedback" style="display:block;">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- ── Footer Aksi ── --}}
            <div class="form-actions">
                <a href="{{ route('repairs.index') }}" class="btn btn-outline">
                    <i class="fas fa-xmark"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary" id="btnSubmit">
                    <i class="fas fa-paper-plane"></i> Kirim Laporan
                </button>
            </div>

        </form>
    </div>
</div>

@push('styles')
<style>
    .repair-form-card { max-width: 740px; }

    .error-summary-title { font-weight: 700; margin-bottom: 4px; }
    .error-summary-list  { margin: 0; padding-left: 16px; }
    .error-summary-list li { font-size: 13px; }

    .form-section-title i,
    .section-title i { margin-right: 5px; }

    /* ── Autocomplete aset ── */
    .asset-autocomplete { position: relative; }

    .asset-status-icon {
        position: absolute; right: 12px; top: 50%;
        transform: translateY(-50%);
        font-size: 14px; pointer-events: none; display: none;
    }
    .asset-status-icon .fa-circle-check       { color: var(--primary); }
    .asset-status-icon .fa-circle-exclamation { color: var(--warning); }

    #namaHint i,
    #dropdownError i,
    #lokasiHint i { margin-right: 3px; }
    #namaHint i      { color: var(--gray-300); }
    #lokasiHint i    { color: var(--gray-300); }

    .dropdown-error {
        display: none;
        color: var(--danger);
        font-size: 12.5px;
        margin-top: 4px;
    }

    .asset-suggestions {
        display: none;
        position: absolute;
        top: 100%; left: 0; right: 0;
        background: #fff;
        border: 1.5px solid var(--primary);
        border-top: none;
        border-radius: 0 0 var(--radius-sm) var(--radius-sm);
        box-shadow: var(--shadow);
        z-index: 50;
        max-height: 240px;
        overflow-y: auto;
    }
    .suggestion-item {
        padding: 10px 14px;
        cursor: pointer;
        font-size: 13.5px;
        border-bottom: 1px solid var(--gray-100);
    }
    .suggestion-item:last-child { border-bottom: none; }
    .suggestion-item .si-row { display: flex; align-items: center; gap: 6px; }
    .suggestion-item .si-row i { color: var(--primary); font-size: 12px; flex-shrink: 0; }
    .suggestion-item .si-name { font-weight: 600; color: var(--gray-800); }
    .suggestion-item .si-code {
        font-size: 11.5px; color: var(--gray-400);
        background: var(--gray-100);
        padding: 1px 6px; border-radius: 4px;
        margin-left: auto; flex-shrink: 0;
    }
    .suggestion-item .si-loc {
        font-size: 11.5px; color: var(--gray-400);
        margin-top: 3px; padding-left: 18px;
    }
    .suggestion-item .si-loc i { font-size: 10px; margin-right: 3px; }
    .suggestion-empty {
        padding: 12px 14px; font-size: 13px;
        color: var(--gray-400); text-align: center;
    }

    /* ── Preview aset terpilih ── */
    .asset-preview {
        display: none;
        background: var(--primary-xlight);
        border: 1px solid var(--primary-light);
        border-radius: var(--radius-sm);
        padding: 11px 14px;
        margin-top: -10px;
        margin-bottom: 16px;
        align-items: center;
        gap: 10px;
    }
    .asset-preview > i { color: var(--primary); font-size: 16px; flex-shrink: 0; }
    .asset-preview-body { min-width: 0; flex: 1; }
    .asset-preview-title  { font-size: 12px; color: var(--primary); font-weight: 700; margin: 0 0 1px; }
    .asset-preview-detail { font-size: 12.5px; color: var(--gray-600); margin: 0; }
    .asset-preview-change {
        margin-left: auto; background: none; border: none; cursor: pointer;
        color: var(--gray-400); font-size: 13px; flex-shrink: 0; padding: 0;
    }

    /* ── Lokasi terkunci (auto-fill dari aset) ── */
    .locked-input {
        background: var(--gray-100);
        color: var(--gray-500);
        cursor: not-allowed;
    }

    /* ── Upload foto ── */
    .foto-preview-grid {
        display: none;
        grid-template-columns: repeat(5, 1fr);
        gap: 10px;
        margin-bottom: 10px;
    }
    .foto-preview-item {
        position: relative; aspect-ratio: 1;
        border-radius: 8px; overflow: hidden;
        border: 1px solid var(--gray-200);
    }
    .foto-preview-item img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .foto-badge-utama {
        position: absolute; top: 4px; left: 4px;
        background: var(--info); color: #fff;
        font-size: 10px; padding: 2px 6px; border-radius: 4px; font-weight: 600;
    }
    .foto-btn-del {
        position: absolute; top: 4px; right: 4px;
        width: 22px; height: 22px; border-radius: 50%;
        background: rgba(0,0,0,.55); border: none; color: #fff;
        cursor: pointer; font-size: 14px; line-height: 1;
        display: flex; align-items: center; justify-content: center;
    }
    .foto-btn-primary {
        position: absolute; bottom: 4px; left: 50%;
        transform: translateX(-50%);
        background: rgba(0,0,0,.55); border: none; color: #fff;
        font-size: 10px; padding: 2px 7px; border-radius: 4px;
        cursor: pointer; white-space: nowrap;
    }
    .foto-counter {
        display: none;
        font-size: 12px; color: var(--gray-500);
        text-align: right; margin-bottom: 8px;
    }
    .foto-dropzone {
        border: 1.5px dashed var(--gray-300);
        border-radius: var(--radius);
        padding: 1.5rem;
        text-align: center;
        cursor: pointer;
        background: var(--gray-50);
        transition: border-color var(--transition), background var(--transition);
    }
    .foto-dropzone:hover { border-color: var(--primary); background: var(--primary-xlight); }
    .foto-dropzone i { font-size: 24px; color: var(--gray-400); display: block; margin-bottom: 6px; }
    .foto-dropzone p { margin: 0; font-size: 13px; color: var(--gray-500); }
    .foto-dropzone span { font-size: 11px; color: var(--gray-400); }

    .form-actions {
        display: flex; gap: 10px; justify-content: flex-end;
        padding-top: 4px;
    }

    @media (max-width: 768px) {
        .form-actions { flex-direction: column-reverse; }
        .form-actions .btn { width: 100%; justify-content: center; }
        .foto-preview-grid { grid-template-columns: repeat(3, 1fr); }
    }
</style>
@endpush

@push('scripts')
@php
$assetsForJs = $assets->map(fn($a) => [
    'id'     => $a->id,
    'nama'   => $a->nama_barang,
    'kode'   => $a->kode_aset,
    'lokasi' => $a->lokasi_barang ?? '',
])->values()->toArray();
@endphp
<script>
// ─── Data aset dari controller (sudah difilter per unit & kondisi) ────────
const ASSETS = @json($assetsForJs);

// ─── Elemen ───────────────────────────────────────────────────────────────
const inputNama     = document.getElementById('inputNamaAset');
const inputAssetId  = document.getElementById('inputAssetId');
const inputNamaHid  = document.getElementById('inputNamaHidden');
const inputLokasi   = document.getElementById('inputLokasi');
const suggestions   = document.getElementById('assetSuggestions');
const preview       = document.getElementById('assetPreview');
const previewDetail = document.getElementById('previewDetail');
const statusIcon    = document.getElementById('statusIcon');
const dropdownError = document.getElementById('dropdownError');
const form          = document.getElementById('repairForm');

// ─── State: apakah sudah pilih dari dropdown ──────────────────────────────
let assetTerpilih = false;

// ─── Helper: escape HTML ──────────────────────────────────────────────────
function escHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

// ─── Sembunyikan saran ────────────────────────────────────────────────────
function hideSuggestions() {
    suggestions.style.display = 'none';
    suggestions.innerHTML     = '';
    inputNama.setAttribute('aria-expanded', 'false');
}

// ─── Update ikon status di kanan input ────────────────────────────────────
function updateStatusIcon() {
    statusIcon.style.display = inputNama.value.trim() ? 'block' : 'none';
    if (assetTerpilih) {
        statusIcon.innerHTML = '<i class="fas fa-circle-check"></i>';
    } else if (inputNama.value.trim()) {
        statusIcon.innerHTML = '<i class="fas fa-circle-exclamation"></i>';
    }
}

// ─── Tampilkan saran: filter starts-with nama atau kode ──────────────────
inputNama.addEventListener('input', function () {
    const q = this.value.trim().toLowerCase();

    // Reset pilihan jika pengguna ubah teks setelah pilih
    if (assetTerpilih) {
        assetTerpilih         = false;
        inputAssetId.value    = '';
        inputNamaHid.value    = '';
        preview.style.display = 'none';
        dropdownError.style.display = 'none';
    }

    updateStatusIcon();

    if (q.length < 1) { hideSuggestions(); return; }

    // Filter: nama atau kode DIAWALI dengan huruf yang diketik
    const matches = ASSETS.filter(a =>
        a.nama.toLowerCase().startsWith(q) ||
        a.kode.toLowerCase().startsWith(q)
    ).slice(0, 10);

    if (!matches.length) {
        suggestions.innerHTML = `
            <div class="suggestion-empty">
                <i class="fas fa-magnifying-glass"></i>
                Tidak ada aset yang berawalan "<strong>${escHtml(this.value.trim())}</strong>"
            </div>`;
        suggestions.style.display = 'block';
        inputNama.setAttribute('aria-expanded', 'true');
        return;
    }

    suggestions.innerHTML = matches.map((a) => `
        <div class="suggestion-item"
             role="option"
             tabindex="-1"
             data-real-id="${a.id}"
             data-nama="${escHtml(a.nama)}"
             data-kode="${escHtml(a.kode)}"
             data-lokasi="${escHtml(a.lokasi)}"
             onclick="selectAsset(this)">
            <div class="si-row">
                <i class="fas fa-box"></i>
                <span class="si-name">${escHtml(a.nama)}</span>
                <span class="si-code">${escHtml(a.kode)}</span>
            </div>
            ${a.lokasi ? `
            <div class="si-loc"><i class="fas fa-location-dot"></i>${escHtml(a.lokasi)}</div>` : ''}
        </div>
    `).join('');

    suggestions.style.display = 'block';
    inputNama.setAttribute('aria-expanded', 'true');
});

// ─── Pilih aset dari saran ────────────────────────────────────────────────
function selectAsset(el) {
    const id     = el.getAttribute('data-real-id');
    const nama   = el.getAttribute('data-nama');
    const kode   = el.getAttribute('data-kode');
    const lokasi = el.getAttribute('data-lokasi');

    // Isi hidden inputs
    inputAssetId.value = id;
    inputNamaHid.value = nama;

    // Tampilkan nama di input teks (read-only setelah pilih)
    inputNama.value = nama;

    // Auto-isi lokasi jika masih kosong
    if (!inputLokasi.value && lokasi) {
        inputLokasi.value = lokasi;
    }

    // Tandai sudah terpilih
    assetTerpilih = true;

    // Sembunyikan error, tampilkan preview
    dropdownError.style.display = 'none';
    previewDetail.textContent   = kode + (lokasi ? ' · ' + lokasi : '');
    preview.style.display       = 'flex';

    updateStatusIcon();
    hideSuggestions();
    inputNama.focus();
}

// ─── Hapus pilihan — pengguna bisa cari ulang ────────────────────────────
function clearAsset() {
    assetTerpilih         = false;
    inputAssetId.value    = '';
    inputNamaHid.value    = '';
    inputNama.value       = '';
    inputLokasi.value     = '';
    preview.style.display = 'none';
    dropdownError.style.display = 'none';
    updateStatusIcon();
    inputNama.focus();
}

// ─── Validasi submit: asset_id wajib terisi ───────────────────────────────
form.addEventListener('submit', function (e) {
    if (!inputAssetId.value) {
        e.preventDefault();
        dropdownError.style.display = 'block';
        inputNama.focus();

        // Scroll ke error
        dropdownError.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});

// ─── Tutup saran jika klik di luar ───────────────────────────────────────
document.addEventListener('click', function (e) {
    if (!inputNama.contains(e.target) && !suggestions.contains(e.target)) {
        hideSuggestions();
    }
});

// ─── Navigasi keyboard ────────────────────────────────────────────────────
inputNama.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        hideSuggestions();
        return;
    }

    if (e.key === 'ArrowDown' && suggestions.style.display !== 'none') {
        e.preventDefault();
        const first = suggestions.querySelector('.suggestion-item');
        if (first) first.focus();
        return;
    }
});

// Navigasi antar item saran dengan arrow key
suggestions.addEventListener('keydown', function (e) {
    const items = [...suggestions.querySelectorAll('.suggestion-item')];
    const idx   = items.indexOf(document.activeElement);

    if (e.key === 'ArrowDown') {
        e.preventDefault();
        if (idx < items.length - 1) items[idx + 1].focus();
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        if (idx > 0) items[idx - 1].focus();
        else inputNama.focus();
    } else if (e.key === 'Enter' && idx >= 0) {
        e.preventDefault();
        selectAsset(items[idx]);
    } else if (e.key === 'Escape') {
        hideSuggestions();
        inputNama.focus();
    }
});

// ─── Hover styling saran (di-handle via CSS :hover sebenarnya cukup,
//      tapi dipertahankan agar konsisten dengan navigasi keyboard/focus) ──
suggestions.addEventListener('mouseover', function (e) {
    const item = e.target.closest('.suggestion-item');
    if (item) {
        suggestions.querySelectorAll('.suggestion-item').forEach(el =>
            el.classList.remove('is-hover'));
        item.classList.add('is-hover');
    }
});
suggestions.addEventListener('mouseout', function (e) {
    const item = e.target.closest('.suggestion-item');
    if (item) item.classList.remove('is-hover');
});

// ─── Init: pulihkan state setelah validasi gagal (old values) ────────────
document.addEventListener('DOMContentLoaded', function () {
    const oldAssetId = '{{ old('asset_id') }}';
    const oldNama    = '{{ old('nama_aset_laporan') }}';

    if (oldAssetId && oldNama) {
        const found = ASSETS.find(a => String(a.id) === String(oldAssetId));
        if (found) {
            inputAssetId.value = found.id;
            inputNamaHid.value = found.nama;
            inputNama.value    = found.nama;
            assetTerpilih      = true;
            previewDetail.textContent = found.kode + (found.lokasi ? ' · ' + found.lokasi : '');
            preview.style.display    = 'flex';
            updateStatusIcon();
        }
    }
});

// ─── Lokasi: tampilkan hint saat aset dipilih ─────────────────────────────
function applyLokasiState(lokasi) {
    const inputLokasi  = document.getElementById('inputLokasi');
    const lokasiHint   = document.getElementById('lokasiHint');
    inputLokasi.value  = lokasi || '';
    inputLokasi.readOnly = true;
    lokasiHint.style.display = 'block';
}

function resetLokasiState() {
    const inputLokasi  = document.getElementById('inputLokasi');
    const lokasiHint   = document.getElementById('lokasiHint');
    inputLokasi.value  = '';
    inputLokasi.readOnly = true;
    lokasiHint.style.display = 'none';
}

// Patch selectAsset agar memanggil applyLokasiState
const _origSelectAsset = selectAsset;
window.selectAsset = function(el) {
    _origSelectAsset(el);
    applyLokasiState(el.getAttribute('data-lokasi'));
};

// Patch clearAsset agar reset lokasi
const _origClearAsset = clearAsset;
window.clearAsset = function() {
    _origClearAsset();
    resetLokasiState();
};

// ─── Sistem foto satu per satu ────────────────────────────────────────────
(function () {
    const MAX        = 5;
    const photos     = [];
    let   primaryIdx = 0;

    const picker     = document.getElementById('foto-picker');
    const grid       = document.getElementById('foto-preview-grid');
    const counter    = document.getElementById('foto-counter');
    const dropzone   = document.getElementById('foto-dropzone');
    const hiddenWrap = document.getElementById('foto-hidden-inputs');

    picker.addEventListener('change', function (e) {
        const file = e.target.files[0];
        picker.value = '';
        if (!file) return;

        if (photos.length >= MAX) {
            alert('Maksimal 5 foto yang dapat diunggah.');
            return;
        }
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran foto "' + file.name + '" melebihi 2 MB.');
            return;
        }

        const reader = new FileReader();
        reader.onload = function (ev) {
            photos.push({ file: file, dataUrl: ev.target.result });
            if (photos.length === 1) primaryIdx = 0;
            render();
        };
        reader.readAsDataURL(file);
    });

    function render() {
        counter.textContent    = photos.length + ' / ' + MAX + ' foto dipilih';
        counter.style.display  = photos.length ? 'block' : 'none';
        grid.style.display     = photos.length ? 'grid'  : 'none';
        grid.innerHTML         = '';
        dropzone.style.display = photos.length >= MAX ? 'none' : 'block';

        photos.forEach(function (p, i) {
            const wrap = document.createElement('div');
            wrap.className = 'foto-preview-item';

            const img = document.createElement('img');
            img.src   = p.dataUrl;
            img.alt   = 'Foto ' + (i + 1);
            wrap.appendChild(img);

            if (i === primaryIdx) {
                const badge = document.createElement('span');
                badge.className   = 'foto-badge-utama';
                badge.textContent = 'Utama';
                wrap.appendChild(badge);
            }

            const btnDel = document.createElement('button');
            btnDel.type      = 'button';
            btnDel.className = 'foto-btn-del';
            btnDel.innerHTML = '&times;';
            btnDel.title     = 'Hapus foto';
            btnDel.onclick   = function () {
                photos.splice(i, 1);
                if (primaryIdx >= photos.length) primaryIdx = 0;
                render();
            };
            wrap.appendChild(btnDel);

            if (i !== primaryIdx) {
                const btnPri = document.createElement('button');
                btnPri.type      = 'button';
                btnPri.className = 'foto-btn-primary';
                btnPri.textContent = 'Jadikan utama';
                btnPri.onclick   = function () { primaryIdx = i; render(); };
                wrap.appendChild(btnPri);
            }

            grid.appendChild(wrap);
        });

        syncHiddenInputs();
    }

    function syncHiddenInputs() {
        const dt      = new DataTransfer();
        const ordered = [photos[primaryIdx]].concat(
            photos.filter(function (_, i) { return i !== primaryIdx; })
        );
        ordered.forEach(function (p) { if (p) dt.items.add(p.file); });

        hiddenWrap.innerHTML = '';
        const inp    = document.createElement('input');
        inp.type     = 'file';
        inp.name     = 'fotos[]';
        inp.multiple = true;
        inp.style.display = 'none';
        inp.files    = dt.files;
        hiddenWrap.appendChild(inp);
    }

    // Validasi foto saat submit — wajib minimal 1
    document.getElementById('repairForm').addEventListener('submit', function (e) {
        if (photos.length === 0) {
            e.preventDefault();
            alert('Foto kerusakan wajib diisi minimal 1 foto.');
            document.getElementById('foto-dropzone').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }, true); // capture: true agar berjalan sebelum validator asset_id
})();
</script>
@endpush

@endsection