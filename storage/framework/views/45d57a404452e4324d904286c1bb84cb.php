
<?php $__env->startSection('title', 'Edit Aset'); ?>
<?php $__env->startSection('page-title', 'Edit Aset'); ?>

<?php $__env->startSection('content'); ?>


<div class="page-header-row">
    <div class="ph-left">
        <h1>Edit Aset</h1>
        <p>Perbarui data aset: <strong><?php echo e($asset->nama_barang); ?></strong></p>
    </div>
    <div class="ph-right">
        <a href="<?php echo e(route('assets.index')); ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?php echo e(route('assets.update', $asset)); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

            
            <p class="section-title">
                Informasi Barang
                <span style="font-weight:400;color:var(--gray-500);text-transform:none;letter-spacing:0;">(tidak dapat diubah)</span>
            </p>

            <div class="readonly-banner">
                <span class="text-muted">Kode Aset:</span>
                <code style="font-weight:700;color:var(--gray-800);margin-left:6px;"><?php echo e($asset->kode_aset); ?></code>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" class="form-control" value="<?php echo e($asset->nama_barang); ?>" disabled>
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <input type="text" class="form-control" value="<?php echo e($asset->kategori); ?>" disabled>
                </div>
                <div class="form-group">
                    <label class="form-label">Unit Kerja</label>
                    <input type="text" class="form-control" value="<?php echo e($asset->unit->nama_unit ?? '-'); ?>" disabled>
                </div>
                <div class="form-group">
                    <label class="form-label">Jumlah Barang</label>
                    <input type="number" class="form-control" value="<?php echo e($asset->jumlah_barang); ?>" disabled>
                </div>
                <div class="form-group">
                    <label class="form-label">Harga Barang (Rp)</label>
                    <input type="number" class="form-control" value="<?php echo e($asset->harga_barang); ?>" disabled>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Pengadaan</label>
                    <input type="date" class="form-control" value="<?php echo e($asset->tanggal_pengadaan?->format('Y-m-d')); ?>" disabled>
                </div>
                <div class="form-group">
                    <label class="form-label">Sumber Dana</label>
                    <input type="text" class="form-control" value="<?php echo e($asset->fundingSource->nama_sumber ?? '-'); ?>" disabled>
                </div>
                <div class="form-group">
                    <label class="form-label">Satuan</label>
                    <input type="text" class="form-control" value="<?php echo e($asset->satuan->nama_satuan ?? '-'); ?>" disabled>
                </div>
            </div>

            
            <p class="section-title" style="margin-top:24px;">Data yang Dapat Diperbarui</p>

            <div class="form-grid">
                <div class="form-group">
                    
                    <label class="form-label">Kondisi Barang <span class="required">*</span></label>
                    <select name="kondisi_barang" class="form-control <?php echo e($errors->has('kondisi_barang') ? 'is-invalid' : ''); ?>">
                        <?php $__currentLoopData = ['aktif' => 'Aktif', 'rusak' => 'Rusak', 'hilang' => 'Hilang', 'habis_pakai' => 'Habis Pakai']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($val); ?>" <?php echo e(old('kondisi_barang', $asset->kondisi_barang)==$val?'selected':''); ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['kondisi_barang'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="invalid-feedback"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <?php if($asset->is_unit_yayasan): ?>
                
                <div class="form-group">
                    <label class="form-label">Lokasi Barang</label>
                    <input type="text" name="lokasi_barang" class="form-control <?php echo e($errors->has('lokasi_barang') ? 'is-invalid' : ''); ?>" placeholder="Contoh: Ruang Rapat Lantai 2" value="<?php echo e(old('lokasi_barang', $asset->lokasi_barang)); ?>">
                    <?php $__errorArgs = ['lokasi_barang'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="invalid-feedback"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <?php else: ?>
                <div class="form-group">
                    <label class="form-label">Lokasi Barang</label>
                    <input type="text" class="form-control" value="<?php echo e($asset->lokasi_barang ?? '-'); ?>" disabled>
                    <p class="form-hint">Lokasi hanya bisa diubah untuk aset unit Yayasan.</p>
                </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3" placeholder="Catatan tambahan..."><?php echo e(old('keterangan', $asset->keterangan)); ?></textarea>
            </div>

            
            <p class="section-title" style="margin-top:24px;">Foto Aset</p>

            <?php if($asset->photos->isNotEmpty()): ?>
            <div class="foto-existing-grid">
                <?php $__currentLoopData = $asset->photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $foto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="foto-existing-item">
                    <img src="<?php echo e(Storage::url($foto->file_path)); ?>" alt="foto aset"
                         style="border-color: <?php echo e($foto->is_primary ? 'var(--primary)' : 'var(--gray-200)'); ?>;">
                    <?php if($foto->is_primary): ?>
                    <span class="foto-existing-badge">Utama</span>
                    <?php endif; ?>
                    <div class="foto-existing-actions">
                        <label>
                            <input type="radio" name="foto_utama_id" value="<?php echo e($foto->id); ?>" <?php echo e($foto->is_primary?'checked':''); ?>> Utama
                        </label>
                        <label>
                            <input type="checkbox" name="hapus_foto[]" value="<?php echo e($foto->id); ?>"> Hapus
                        </label>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php else: ?>
            <p class="text-muted" style="font-size:13px;margin-bottom:12px;">Belum ada foto untuk aset ini.</p>
            <?php endif; ?>

            <div class="form-group" id="foto-baru-group">
                <label class="form-label">Tambah Foto Baru</label>

                
                <div id="foto-baru-grid" class="foto-preview-grid"></div>
                <p id="foto-baru-counter" class="foto-counter"></p>

                
                <div id="foto-baru-dropzone" class="foto-dropzone" onclick="document.getElementById('foto-baru-picker').click()">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>Klik untuk pilih foto baru</p>
                    <span>JPG / PNG / WEBP &middot; maks. 2 MB per foto</span>
                    <span id="foto-baru-sisa-hint" class="foto-sisa-hint"></span>
                </div>

                <input type="file" id="foto-baru-picker"
                    accept="image/jpg,image/jpeg,image/png,image/webp"
                    style="display:none">

                
                <div id="foto-baru-hidden"></div>
            </div>

            <div class="form-actions">
                <a href="<?php echo e(route('assets.show', $asset)); ?>" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
    .readonly-banner {
        background: var(--gray-50);
        border: 1px solid var(--gray-200);
        border-radius: var(--radius-sm);
        padding: 10px 14px;
        margin-bottom: 16px;
        font-size: 13px;
    }

    /* ── Dropzone foto (dipakai juga di halaman create) ── */
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
    .foto-sisa-hint { display: block; margin-top: 2px; }

    .foto-preview-grid {
        display: none;
        grid-template-columns: repeat(auto-fill, minmax(84px, 1fr));
        gap: 10px;
        margin-bottom: 10px;
    }
    .foto-preview-grid.has-items { display: grid; }
    .foto-counter {
        display: none;
        font-size: 12px; color: var(--gray-500);
        text-align: right; margin-bottom: 8px;
    }
    .foto-counter.has-items { display: block; }

    /* ── Foto yang sudah ada (existing) — auto-fill agar tidak fix 80px berderet sampai overflow ── */
    .foto-existing-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(84px, 1fr));
        gap: 14px;
        margin-bottom: 16px;
    }
    .foto-existing-item { position: relative; }
    .foto-existing-item img {
        width: 100%; aspect-ratio: 1; object-fit: cover;
        border-radius: 6px; border-width: 2px; border-style: solid; display: block;
    }
    .foto-existing-badge {
        position: absolute; top: 4px; left: 4px;
        background: var(--primary); color: #fff;
        font-size: 9px; padding: 1px 5px; border-radius: 4px;
    }
    .foto-existing-actions {
        margin-top: 4px; display: flex; gap: 8px; flex-wrap: wrap;
    }
    .foto-existing-actions label {
        font-size: 11px; display: flex; align-items: center; gap: 3px; cursor: pointer;
        color: var(--gray-600); white-space: nowrap;
    }

    .form-actions {
        display: flex; gap: 10px; justify-content: flex-end; margin-top: 8px;
    }
    @media (max-width: 768px) {
        .form-actions { flex-direction: column-reverse; }
        .form-actions .btn { width: 100%; justify-content: center; }
        .foto-preview-grid, .foto-existing-grid { grid-template-columns: repeat(auto-fill, minmax(72px, 1fr)); }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(function () {
    const MAX_TOTAL   = 5;
    const existingCount = <?php echo e($asset->photos->count()); ?>;
    const photos      = [];

    const picker      = document.getElementById('foto-baru-picker');
    const grid        = document.getElementById('foto-baru-grid');
    const counter     = document.getElementById('foto-baru-counter');
    const dropzone    = document.getElementById('foto-baru-dropzone');
    const hiddenWrap  = document.getElementById('foto-baru-hidden');
    const sisaHint    = document.getElementById('foto-baru-sisa-hint');

    // Hitung slot tersisa: total maks 5 dikurangi foto lama yang belum dicentang hapus
    function getHapusCount() {
        return document.querySelectorAll('input[name="hapus_foto[]"]:checked').length;
    }

    function getMaxBaru() {
        const active = existingCount - getHapusCount();
        return Math.max(0, MAX_TOTAL - active);
    }

    function updateSisaHint() {
        const sisa = getMaxBaru() - photos.length;
        if (sisa > 0) {
            sisaHint.textContent = 'Dapat menambah ' + sisa + ' foto lagi (total maks. ' + MAX_TOTAL + ')';
        } else {
            sisaHint.textContent = 'Batas foto tercapai (maks. ' + MAX_TOTAL + ' foto per aset)';
        }
    }

    // Update dropzone jika checkbox hapus diubah
    document.querySelectorAll('input[name="hapus_foto[]"]').forEach(function (cb) {
        cb.addEventListener('change', function () {
            render();
        });
    });

    picker.addEventListener('change', function (e) {
        const file = e.target.files[0];
        picker.value = '';
        if (!file) return;

        if (photos.length >= getMaxBaru()) {
            alert('Tidak dapat menambah foto. Total foto aset tidak boleh melebihi ' + MAX_TOTAL + ' foto.');
            return;
        }
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran foto "' + file.name + '" melebihi 2 MB.');
            return;
        }

        const reader = new FileReader();
        reader.onload = function (ev) {
            photos.push({ file: file, dataUrl: ev.target.result });
            render();
        };
        reader.readAsDataURL(file);
    });

    function render() {
        const maxBaru = getMaxBaru();

        counter.textContent   = photos.length + ' foto baru dipilih';
        counter.classList.toggle('has-items', photos.length > 0);
        grid.classList.toggle('has-items', photos.length > 0);
        grid.innerHTML        = '';

        // Sembunyikan dropzone jika slot penuh
        dropzone.style.display = photos.length >= maxBaru ? 'none' : 'block';
        updateSisaHint();

        photos.forEach(function (p, i) {
            const wrap = document.createElement('div');
            wrap.style.cssText = 'position:relative;aspect-ratio:1;border-radius:8px;'
                               + 'overflow:hidden;border:1px solid #e5e7eb;';

            const img = document.createElement('img');
            img.src   = p.dataUrl;
            img.alt   = 'Foto baru ' + (i + 1);
            img.style.cssText = 'width:100%;height:100%;object-fit:cover;display:block;';
            wrap.appendChild(img);

            // Badge urutan
            const badge = document.createElement('span');
            badge.textContent = 'Baru ' + (i + 1);
            badge.style.cssText = 'position:absolute;top:4px;left:4px;background:#0891b2;'
                + 'color:#fff;font-size:10px;padding:2px 6px;border-radius:4px;font-weight:600;';
            wrap.appendChild(badge);

            // Tombol hapus
            const btnDel = document.createElement('button');
            btnDel.type  = 'button';
            btnDel.innerHTML = '&times;';
            btnDel.title = 'Batalkan foto ini';
            btnDel.style.cssText = 'position:absolute;top:4px;right:4px;width:22px;height:22px;'
                + 'border-radius:50%;background:rgba(0,0,0,.55);border:none;color:#fff;'
                + 'cursor:pointer;font-size:14px;line-height:1;display:flex;'
                + 'align-items:center;justify-content:center;';
            btnDel.onclick = function () {
                photos.splice(i, 1);
                render();
            };
            wrap.appendChild(btnDel);

            grid.appendChild(wrap);
        });

        syncHiddenInputs();
    }

    function syncHiddenInputs() {
        const dt = new DataTransfer();
        photos.forEach(function (p) { dt.items.add(p.file); });

        hiddenWrap.innerHTML = '';
        if (photos.length > 0) {
            const inp    = document.createElement('input');
            inp.type     = 'file';
            inp.name     = 'fotos_baru[]';
            inp.multiple = true;
            inp.style.display = 'none';
            inp.files    = dt.files;
            hiddenWrap.appendChild(inp);
        }
    }

    // Init hint saat halaman pertama kali load
    updateSisaHint();
})();
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\SIMAS-ASH-SHIDDIIQI\resources\views/assets/edit.blade.php ENDPATH**/ ?>