
<?php $__env->startSection('title', 'Laporkan Kerusakan'); ?>
<?php $__env->startSection('page-title', 'Perbaikan Aset'); ?>
<?php $__env->startSection('page-parent', 'Laporkan Kerusakan'); ?>

<?php $__env->startSection('content'); ?>


<div class="page-header-row">
    <div class="ph-left">
        <h1>Laporkan Kerusakan Aset</h1>
        <p>Isi formulir berikut untuk melaporkan kerusakan aset</p>
    </div>
    <div class="ph-right">
        <a href="<?php echo e(route('repairs.index')); ?>" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>


<?php if($errors->any()): ?>
<div class="alert alert-error">
    <i class="fas fa-triangle-exclamation"></i>
    <div>
        <p style="font-weight:700;margin-bottom:4px;">Terdapat kesalahan:</p>
        <ul style="margin:0;padding-left:16px;">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li style="font-size:13px;"><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
</div>
<?php endif; ?>

<div class="card" style="max-width:740px;">
    <div class="card-body">
        <form action="<?php echo e(route('repairs.store')); ?>" method="POST" enctype="multipart/form-data" id="repairForm">
            <?php echo csrf_field(); ?>

            
            <input type="hidden" name="asset_id"          id="inputAssetId"   value="<?php echo e(old('asset_id')); ?>">
            <input type="hidden" name="nama_aset_laporan" id="inputNamaHidden" value="<?php echo e(old('nama_aset_laporan')); ?>">

            
            <div class="form-section">
                <p class="form-section-title">
                    <i class="fas fa-box" style="margin-right:5px;"></i>Barang yang Rusak
                </p>

                
                <div class="form-group" style="position:relative;">
                    <label class="form-label" for="inputNamaAset">
                        Nama Barang yang Rusak <span class="required">*</span>
                    </label>

                    
                    <div class="input-wrap" style="position:relative;">
                        <i class="input-icon fas fa-box-open"></i>
                        <input type="text"
                            id="inputNamaAset"
                            class="form-control <?php $__errorArgs = ['nama_aset_laporan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> <?php $__errorArgs = ['asset_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="Ketik nama atau kode aset..."
                            value="<?php echo e(old('nama_aset_laporan')); ?>"
                            autocomplete="off"
                            autofocus
                            aria-autocomplete="list"
                            aria-controls="assetSuggestions"
                            aria-expanded="false">
                        
                        <span id="statusIcon" style="
                            position:absolute;right:12px;top:50%;transform:translateY(-50%);
                            font-size:14px;pointer-events:none;display:none;">
                        </span>
                    </div>

                    <p class="form-hint" id="namaHint">
                        <i class="fas fa-circle-info" style="color:var(--gray-300);margin-right:3px;"></i>
                        Ketik minimal 1 huruf — aset yang berawalan huruf tersebut akan muncul. Wajib pilih dari daftar.
                        <?php if(auth()->user()->unit_id && !auth()->user()->isAdminUtama() && !auth()->user()->isKepalaYayasan()): ?>
                            &nbsp;Menampilkan aset unit <strong><?php echo e(auth()->user()->unit->nama_unit); ?></strong>.
                        <?php endif; ?>
                    </p>

                    
                    <p id="dropdownError" style="display:none;color:var(--danger,#dc3545);font-size:12.5px;margin-top:4px;">
                        <i class="fas fa-circle-exclamation" style="margin-right:3px;"></i>
                        Pilih barang dari daftar saran — tidak bisa diisi manual.
                    </p>
                    <?php $__errorArgs = ['nama_aset_laporan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="invalid-feedback"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <?php $__errorArgs = ['asset_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>          <p class="invalid-feedback"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    
                    <div id="assetSuggestions"
                         role="listbox"
                         style="
                            display:none;
                            position:absolute;
                            top:100%; left:0; right:0;
                            background:#fff;
                            border:1.5px solid var(--primary);
                            border-top:none;
                            border-radius:0 0 var(--radius-sm) var(--radius-sm);
                            box-shadow:var(--shadow);
                            z-index:50;
                            max-height:240px;
                            overflow-y:auto;">
                    </div>
                </div>

                
                <div id="assetPreview" style="
                    display:none;
                    background:var(--primary-xlight);
                    border:1px solid var(--primary-light);
                    border-radius:var(--radius-sm);
                    padding:11px 14px;
                    margin-top:-10px;
                    margin-bottom:16px;
                    align-items:center;
                    gap:10px;">
                    <i class="fas fa-circle-check" style="color:var(--primary);font-size:16px;flex-shrink:0;"></i>
                    <div style="min-width:0;flex:1;">
                        <p style="font-size:12px;color:var(--primary);font-weight:700;margin:0 0 1px;">Aset terdaftar dipilih</p>
                        <p id="previewDetail" style="font-size:12.5px;color:var(--gray-600);margin:0;"></p>
                    </div>
                    <button type="button" onclick="clearAsset()"
                        style="margin-left:auto;background:none;border:none;cursor:pointer;
                               color:var(--gray-400);font-size:13px;flex-shrink:0;padding:0;"
                        title="Ganti pilihan">
                        <i class="fas fa-pen-to-square"></i> Ganti
                    </button>
                </div>

                
                <div class="form-group">
                    <label class="form-label" for="inputLokasi">Lokasi Kerusakan</label>
                    <div class="input-wrap">
                        <i class="input-icon fas fa-location-dot"></i>
                        <input type="text" name="lokasi_kerusakan" id="inputLokasi"
                            class="form-control <?php $__errorArgs = ['lokasi_kerusakan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="Pilih barang terlebih dahulu..."
                            value="<?php echo e(old('lokasi_kerusakan')); ?>"
                            readonly
                            style="background:#f3f4f6;color:#6b7280;cursor:not-allowed;">
                    </div>
                    <p class="form-hint" id="lokasiHint" style="display:none;">
                        <i class="fas fa-lock" style="color:var(--gray-300);margin-right:3px;"></i>
                        Lokasi diambil otomatis dari data aset dan tidak dapat diubah.
                    </p>
                    <?php $__errorArgs = ['lokasi_kerusakan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="invalid-feedback"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            
            <div class="form-section">
                <p class="form-section-title">
                    <i class="fas fa-triangle-exclamation" style="margin-right:5px;"></i>Detail Kerusakan
                </p>

                <div class="form-group">
                    <label class="form-label" for="deskripsiKerusakan">
                        Deskripsi Kerusakan <span class="required">*</span>
                    </label>
                    <textarea name="deskripsi_kerusakan" id="deskripsiKerusakan" rows="4"
                        class="form-control <?php $__errorArgs = ['deskripsi_kerusakan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        placeholder="Jelaskan kerusakan: gejala, kapan terjadi, kondisi saat ini..."><?php echo e(old('deskripsi_kerusakan')); ?></textarea>
                    <?php $__errorArgs = ['deskripsi_kerusakan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="invalid-feedback"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group" id="foto-group">
                    <label class="form-label">
                        Foto Kerusakan <span class="required">*</span>
                    </label>

                    
                    <div id="foto-preview-grid"
                        style="display:none; grid-template-columns:repeat(5,1fr); gap:10px; margin-bottom:10px;">
                    </div>
                    <p id="foto-counter"
                    style="display:none; font-size:12px; color:#6b7280; text-align:right; margin-bottom:8px;">
                    </p>

                    
                    <div id="foto-dropzone"
                        onclick="document.getElementById('foto-picker').click()"
                        style="border:1.5px dashed #d1d5db; border-radius:10px; padding:1.5rem;
                                text-align:center; cursor:pointer; background:#f9fafb;
                                transition:border-color .15s,background .15s;"
                        onmouseover="this.style.borderColor='#2563eb';this.style.background='#eff6ff'"
                        onmouseout="this.style.borderColor='#d1d5db';this.style.background='#f9fafb'">
                        <i class="fas fa-camera" style="font-size:24px; color:#9ca3af; display:block; margin-bottom:6px;"></i>
                        <p style="margin:0; font-size:13px; color:#6b7280;">Klik untuk pilih foto kerusakan</p>
                        <span style="font-size:11px; color:#9ca3af;">JPG / PNG / WEBP · maks. 2 MB · hingga 5 foto · wajib minimal 1</span>
                    </div>

                    <input type="file" id="foto-picker"
                        accept="image/jpg,image/jpeg,image/png,image/webp"
                        style="display:none">

                    
                    <div id="foto-hidden-inputs"></div>

                    <?php $__errorArgs = ['fotos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>   <p class="invalid-feedback" style="display:block;"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <?php $__errorArgs = ['fotos.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="invalid-feedback" style="display:block;"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            
            <div style="display:flex;gap:10px;justify-content:flex-end;padding-top:4px;">
                <a href="<?php echo e(route('repairs.index')); ?>" class="btn btn-outline">
                    <i class="fas fa-xmark"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary" id="btnSubmit">
                    <i class="fas fa-paper-plane"></i> Kirim Laporan
                </button>
            </div>

        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<?php
$assetsForJs = $assets->map(fn($a) => [
    'id'     => $a->id,
    'nama'   => $a->nama_barang,
    'kode'   => $a->kode_aset,
    'lokasi' => $a->lokasi_barang ?? '',
])->values()->toArray();
?>
<script>
// ─── Data aset dari controller (sudah difilter per unit & kondisi) ────────
const ASSETS = <?php echo json_encode($assetsForJs, 15, 512) ?>;

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
        statusIcon.innerHTML = '<i class="fas fa-circle-check" style="color:var(--primary);"></i>';
    } else if (inputNama.value.trim()) {
        statusIcon.innerHTML = '<i class="fas fa-circle-exclamation" style="color:var(--warning,#f59e0b);"></i>';
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
            <div style="padding:12px 14px;font-size:13px;color:var(--gray-400);text-align:center;">
                <i class="fas fa-magnifying-glass" style="margin-right:5px;"></i>
                Tidak ada aset yang berawalan "<strong>${escHtml(this.value.trim())}</strong>"
            </div>`;
        suggestions.style.display = 'block';
        inputNama.setAttribute('aria-expanded', 'true');
        return;
    }

    suggestions.innerHTML = matches.map((a, i) => `
        <div class="suggestion-item"
             role="option"
             tabindex="-1"
             data-real-id="${a.id}"
             data-nama="${escHtml(a.nama)}"
             data-kode="${escHtml(a.kode)}"
             data-lokasi="${escHtml(a.lokasi)}"
             onclick="selectAsset(this)"
             style="padding:10px 14px;cursor:pointer;font-size:13.5px;
                    border-bottom:1px solid var(--gray-100);
                    ${i === matches.length - 1 ? 'border-bottom:none;' : ''}">
            <div style="display:flex;align-items:center;gap:6px;">
                <i class="fas fa-box" style="color:var(--primary);font-size:12px;flex-shrink:0;"></i>
                <span style="font-weight:600;color:var(--gray-800);">${escHtml(a.nama)}</span>
                <span style="font-size:11.5px;color:var(--gray-400);background:var(--gray-100);
                             padding:1px 6px;border-radius:4px;margin-left:auto;flex-shrink:0;">
                    ${escHtml(a.kode)}
                </span>
            </div>
            ${a.lokasi ? `
            <div style="font-size:11.5px;color:var(--gray-400);margin-top:3px;padding-left:18px;">
                <i class="fas fa-location-dot" style="font-size:10px;margin-right:3px;"></i>${escHtml(a.lokasi)}
            </div>` : ''}
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

// ─── Hover styling saran ──────────────────────────────────────────────────
suggestions.addEventListener('mouseover', function (e) {
    const item = e.target.closest('.suggestion-item');
    if (item) {
        suggestions.querySelectorAll('.suggestion-item').forEach(el =>
            el.style.background = '');
        item.style.background = 'var(--primary-xlight)';
    }
});
suggestions.addEventListener('mouseout', function (e) {
    const item = e.target.closest('.suggestion-item');
    if (item) item.style.background = '';
});

// ─── Init: pulihkan state setelah validasi gagal (old values) ────────────
document.addEventListener('DOMContentLoaded', function () {
    const oldAssetId = '<?php echo e(old('asset_id')); ?>';
    const oldNama    = '<?php echo e(old('nama_aset_laporan')); ?>';

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
    inputLokasi.style.background = '#f3f4f6';
    inputLokasi.style.color      = '#6b7280';
    inputLokasi.style.cursor     = 'not-allowed';
    lokasiHint.style.display     = 'block';
}

function resetLokasiState() {
    const inputLokasi  = document.getElementById('inputLokasi');
    const lokasiHint   = document.getElementById('lokasiHint');
    inputLokasi.value  = '';
    inputLokasi.readOnly = true;
    inputLokasi.style.background = '#f3f4f6';
    inputLokasi.style.color      = '#6b7280';
    inputLokasi.style.cursor     = 'not-allowed';
    lokasiHint.style.display     = 'none';
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
            wrap.style.cssText = 'position:relative;aspect-ratio:1;border-radius:8px;'
                               + 'overflow:hidden;border:1px solid #e5e7eb;';

            const img = document.createElement('img');
            img.src   = p.dataUrl;
            img.alt   = 'Foto ' + (i + 1);
            img.style.cssText = 'width:100%;height:100%;object-fit:cover;display:block;';
            wrap.appendChild(img);

            if (i === primaryIdx) {
                const badge = document.createElement('span');
                badge.textContent = 'Utama';
                badge.style.cssText = 'position:absolute;top:4px;left:4px;background:#2563eb;'
                    + 'color:#fff;font-size:10px;padding:2px 6px;border-radius:4px;font-weight:600;';
                wrap.appendChild(badge);
            }

            const btnDel = document.createElement('button');
            btnDel.type  = 'button';
            btnDel.innerHTML = '&times;';
            btnDel.title = 'Hapus foto';
            btnDel.style.cssText = 'position:absolute;top:4px;right:4px;width:22px;height:22px;'
                + 'border-radius:50%;background:rgba(0,0,0,.55);border:none;color:#fff;'
                + 'cursor:pointer;font-size:14px;line-height:1;display:flex;'
                + 'align-items:center;justify-content:center;';
            btnDel.onclick = function () {
                photos.splice(i, 1);
                if (primaryIdx >= photos.length) primaryIdx = 0;
                render();
            };
            wrap.appendChild(btnDel);

            if (i !== primaryIdx) {
                const btnPri = document.createElement('button');
                btnPri.type = 'button';
                btnPri.textContent = 'Jadikan utama';
                btnPri.style.cssText = 'position:absolute;bottom:4px;left:50%;transform:translateX(-50%);'
                    + 'background:rgba(0,0,0,.55);border:none;color:#fff;font-size:10px;'
                    + 'padding:2px 7px;border-radius:4px;cursor:pointer;white-space:nowrap;';
                btnPri.onclick = function () { primaryIdx = i; render(); };
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
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\SIMAS-ASH-SHIDDIIQI\resources\views/repairs/create.blade.php ENDPATH**/ ?>