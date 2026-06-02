
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
        <form action="<?php echo e(route('repairs.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            
            <input type="hidden" name="asset_id"  id="inputAssetId"  value="<?php echo e(old('asset_id')); ?>">
            <input type="hidden" name="mode"       id="inputMode"     value="<?php echo e(old('mode', 'manual')); ?>">

            
            <div class="form-section">
                <p class="form-section-title">
                    <i class="fas fa-box" style="margin-right:5px;"></i>Barang yang Rusak
                </p>

                
                <div class="form-group" style="position:relative;">
                    <label class="form-label">
                        Nama Barang yang Rusak <span class="required">*</span>
                    </label>
                    <div class="input-wrap">
                        <i class="input-icon fas fa-box-open"></i>
                        <input type="text"
                            name="nama_aset_laporan"
                            id="inputNamaAset"
                            class="form-control <?php $__errorArgs = ['nama_aset_laporan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="Ketik nama barang atau pilih dari daftar aset..."
                            value="<?php echo e(old('nama_aset_laporan')); ?>"
                            autocomplete="off"
                            autofocus>
                    </div>
                    <p class="form-hint" id="namaHint">
                        Tulis nama barang sedetail mungkin. Saran akan muncul jika barang terdaftar
                        <?php if(auth()->user()->unit_id && !auth()->user()->isAdminUtama() && !auth()->user()->isKepalaYayasan()): ?>
                            di unit <strong><?php echo e(auth()->user()->unit->nama_unit); ?></strong>
                        <?php endif; ?>
                    </p>
                    <?php $__errorArgs = ['nama_aset_laporan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="invalid-feedback"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    
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

                
                <div class="form-group">
                    <label class="form-label">Lokasi Kerusakan</label>
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
                            placeholder="Contoh: Ruang Kelas 7A, Lab Komputer Lantai 2"
                            value="<?php echo e(old('lokasi_kerusakan')); ?>">
                    </div>
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
                    <label class="form-label">
                        Deskripsi Kerusakan <span class="required">*</span>
                    </label>
                    <textarea name="deskripsi_kerusakan" rows="4"
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

                
                <div class="form-group">
                    <label class="form-label">
                        Foto Kerusakan <span class="required">*</span>
                    </label>
                    <input type="file" name="fotos[]"
                        class="form-control <?php $__errorArgs = ['fotos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> <?php $__errorArgs = ['fotos.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        accept=".jpg,.jpeg,.png,.webp"
                        multiple
                        required>
                    <p class="form-hint">
                        <i class="fas fa-circle-info" style="color:var(--gray-300);margin-right:3px;"></i>
                        Wajib diisi &middot; Format JPG / PNG / WEBP &middot; Maks. 2 MB per foto &middot; Hingga 5 foto &middot; Wajib minimal 1 foto
                    </p>
                    <?php $__errorArgs = ['fotos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>   <p class="invalid-feedback"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <?php $__errorArgs = ['fotos.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="invalid-feedback"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            
            <div style="display:flex;gap:10px;justify-content:flex-end;padding-top:4px;">
                <a href="<?php echo e(route('repairs.index')); ?>" class="btn btn-outline">
                    <i class="fas fa-xmark"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Kirim Laporan
                </button>
            </div>

        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<?php
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
?>
<script>
// Data aset dari controller, difilter per unit di RepairController::create()
const ASSETS = <?php echo json_encode($assetsForJs, 15, 512) ?>;

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
             data-id="<?php echo e(''); ?>"
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
    const oldAssetId = '<?php echo e(old('asset_id')); ?>';
    const oldNama    = '<?php echo e(old('nama_aset_laporan')); ?>';

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
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\SIMAS-ASH-SHIDDIIQI\resources\views/repairs/create.blade.php ENDPATH**/ ?>