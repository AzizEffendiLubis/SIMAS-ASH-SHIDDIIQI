@extends('layouts.app')
@section('title', 'Update Perbaikan – ' . $repair->kode_perbaikan)
@section('page-title', 'Perbaikan Aset')
@section('page-parent', 'Update Laporan')

@section('content')

{{-- ── Page Header ── --}}
<div class="page-header-row">
    <div class="ph-left">
        <h1>Update Laporan Perbaikan</h1>
        <p>Kode: <strong style="color:var(--gray-700);">{{ $repair->kode_perbaikan }}</strong></p>
    </div>
    <div class="ph-right">
        <a href="{{ route('repairs.show', $repair) }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="card" style="max-width:740px;">
    <div class="card-body">

        {{-- ── Info Laporan (read-only) ── --}}
        <div class="form-section">
            <p class="section-title"><i class="fas fa-circle-info" style="margin-right:5px;"></i>Info Laporan</p>
            <div class="form-grid" style="gap:12px;margin-bottom:12px;">
                <div>
                    <p style="font-size:12px;color:var(--gray-400);margin-bottom:3px;">Nama Barang (Laporan)</p>
                    <p style="font-weight:600;font-size:14px;color:var(--gray-700);">{{ $repair->nama_aset_laporan }}</p>
                    {{-- asset adalah FK opsional — tampilkan kode jika sudah dikaitkan --}}
                    @if($repair->asset)
                    <p style="font-size:12px;color:var(--gray-400);">{{ $repair->asset->kode_aset }} · {{ $repair->asset->unit->nama_unit ?? '' }}</p>
                    @endif
                </div>
                <div>
                    <p style="font-size:12px;color:var(--gray-400);margin-bottom:3px;">Lokasi Kerusakan</p>
                    <p style="font-weight:600;font-size:14px;color:var(--gray-700);">{{ $repair->lokasi_kerusakan ?? '-' }}</p>
                </div>
                <div>
                    <p style="font-size:12px;color:var(--gray-400);margin-bottom:3px;">Tanggal Laporan</p>
                    <p style="font-weight:600;font-size:14px;color:var(--gray-700);">{{ $repair->tanggal_laporan->format('d M Y') }}</p>
                </div>
                <div>
                    <p style="font-size:12px;color:var(--gray-400);margin-bottom:3px;">Dilaporkan Oleh</p>
                    <p style="font-weight:600;font-size:14px;color:var(--gray-700);">{{ $repair->pelapor->name ?? '-' }}</p>
                </div>
            </div>
            <div>
                <p style="font-size:12px;color:var(--gray-400);margin-bottom:5px;">Deskripsi Kerusakan</p>
                <p style="font-size:13.5px;color:var(--gray-700);background:var(--gray-50);border:1px solid var(--gray-200);border-radius:var(--radius-sm);padding:10px 13px;line-height:1.6;">
                    {{ $repair->deskripsi_kerusakan }}
                </p>
            </div>

            {{-- Foto kerusakan (jika ada) --}}
            @if($repair->photos->isNotEmpty())
            <div style="margin-top:12px;">
                <p style="font-size:12px;color:var(--gray-400);margin-bottom:6px;">Foto Kerusakan</p>
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    @foreach($repair->photos as $foto)
                    <a href="{{ Storage::url($foto->file_path) }}" target="_blank">
                        <img src="{{ Storage::url($foto->file_path) }}" alt="Foto kerusakan"
                             style="width:72px;height:72px;object-fit:cover;border-radius:var(--radius-sm);border:1px solid var(--gray-200);">
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <form action="{{ route('repairs.update', $repair) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- ════════════════════════════════════════
                 FORM TEKNISI
                 Hanya bisa set: status (sedang_diperbaiki | selesai),
                 tindakan_perbaikan (wajib), biaya_perbaikan (opsional).
                 Tidak bisa kembali ke 'pending'.
                 Dokumen UC-T-02: "pending, sedang diperbaiki, selesai."
            ════════════════════════════════════════ --}}
            @if(auth()->user()->isTeknisi())

            <div class="form-section">
                <p class="section-title"><i class="fas fa-pen-to-square" style="margin-right:5px;"></i>Update Progres</p>

                <div class="form-group">
                    <label class="form-label">
                        Status Perbaikan <span class="required">*</span>
                    </label>
                    <select name="status" class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}">
                        <option value="sedang_diperbaiki"
                            {{ old('status', $repair->status) === 'sedang_diperbaiki' ? 'selected' : '' }}>
                            Sedang Diperbaiki
                        </option>
                        <option value="selesai"
                            {{ old('status', $repair->status) === 'selesai' ? 'selected' : '' }}>
                            Selesai
                        </option>
                    </select>
                    @error('status')
                    <p class="invalid-feedback">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Tindakan Perbaikan <span class="required">*</span>
                    </label>
                    <textarea name="tindakan_perbaikan" rows="4"
                        class="form-control {{ $errors->has('tindakan_perbaikan') ? 'is-invalid' : '' }}"
                        placeholder="Jelaskan tindakan perbaikan yang telah dilakukan...">{{ old('tindakan_perbaikan', $repair->tindakan_perbaikan) }}</textarea>
                    @error('tindakan_perbaikan')
                    <p class="invalid-feedback">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Biaya Perbaikan (Rp)</label>
                    <div class="input-wrap">
                        <i class="input-icon fas fa-money-bill-wave"></i>
                        <input type="number" name="biaya_perbaikan" class="form-control"
                            min="0" step="1000"
                            value="{{ old('biaya_perbaikan', $repair->biaya_perbaikan) }}"
                            placeholder="0">
                    </div>
                    <p class="form-hint">Kosongkan jika biaya belum diketahui</p>
                </div>
            </div>

            {{-- ════════════════════════════════════════
                 FORM ADMIN UTAMA
                 Bisa edit semua field: nama laporan, deskripsi, lokasi,
                 status (semua opsi termasuk pending), tindakan, biaya,
                 kaitkan ke aset (asset_id FK opsional), assign teknisi.
                 Dokumen: "Petugas perbaikan tidak ditampilkan kepada pengguna pelapor."
            ════════════════════════════════════════ --}}
            @else

            <div class="form-section">
                <p class="section-title"><i class="fas fa-file-pen" style="margin-right:5px;"></i>Detail Laporan</p>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">
                            Nama Barang (Laporan) <span class="required">*</span>
                        </label>
                        <input type="text" name="nama_aset_laporan"
                            class="form-control {{ $errors->has('nama_aset_laporan') ? 'is-invalid' : '' }}"
                            value="{{ old('nama_aset_laporan', $repair->nama_aset_laporan) }}">
                        @error('nama_aset_laporan')
                        <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Status Perbaikan <span class="required">*</span>
                        </label>
                        <select name="status" class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}">
                            <option value="pending"
                                {{ old('status', $repair->status) === 'pending' ? 'selected' : '' }}>
                                Menunggu
                            </option>
                            <option value="sedang_diperbaiki"
                                {{ old('status', $repair->status) === 'sedang_diperbaiki' ? 'selected' : '' }}>
                                Sedang Diperbaiki
                            </option>
                            <option value="selesai"
                                {{ old('status', $repair->status) === 'selesai' ? 'selected' : '' }}>
                                Selesai
                            </option>
                            <option value="tidak_dapat_diperbaiki"
                                {{ old('status', $repair->status) === 'tidak_dapat_diperbaiki' ? 'selected' : '' }}>
                                Tidak Dapat Diperbaiki
                            </option>
                        </select>
                        @error('status')
                        <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Deskripsi Kerusakan <span class="required">*</span>
                    </label>
                    <textarea name="deskripsi_kerusakan" rows="3"
                        class="form-control {{ $errors->has('deskripsi_kerusakan') ? 'is-invalid' : '' }}">{{ old('deskripsi_kerusakan', $repair->deskripsi_kerusakan) }}</textarea>
                    @error('deskripsi_kerusakan')
                    <p class="invalid-feedback">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Lokasi Kerusakan</label>
                    <div class="input-wrap">
                        <i class="input-icon fas fa-location-dot"></i>
                        <input type="text" name="lokasi_kerusakan"
                            class="form-control {{ $errors->has('lokasi_kerusakan') ? 'is-invalid' : '' }}"
                            value="{{ old('lokasi_kerusakan', $repair->lokasi_kerusakan) }}"
                            placeholder="Contoh: Ruang Kelas 7A, Lab Komputer">
                    </div>
                    @error('lokasi_kerusakan')
                    <p class="invalid-feedback">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tindakan Perbaikan</label>
                    <textarea name="tindakan_perbaikan" rows="3"
                        class="form-control {{ $errors->has('tindakan_perbaikan') ? 'is-invalid' : '' }}"
                        placeholder="Tindakan yang telah atau akan dilakukan...">{{ old('tindakan_perbaikan', $repair->tindakan_perbaikan) }}</textarea>
                    @error('tindakan_perbaikan')
                    <p class="invalid-feedback">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-section">
                <p class="section-title"><i class="fas fa-link" style="margin-right:5px;"></i>Penugasan & Keterkaitan Aset</p>

                <div class="form-grid">
                    <div class="form-group">
                        {{-- asset_id: FK opsional ke tabel assets.
                             Dikaitkan Admin Utama setelah verifikasi laporan.
                             Dokumen: "FK opsional ke assets — bisa dikaitkan admin setelah verifikasi."
                             $assets dikirim dari RepairController::edit().
                             Jika belum ada di controller, fallback query di sini. --}}
                        <label class="form-label">Kaitkan ke Aset</label>
                        <select name="asset_id" class="form-control {{ $errors->has('asset_id') ? 'is-invalid' : '' }}">
                            <option value="">— Belum dikaitkan —</option>
                            @foreach($assets ?? \App\Models\Asset::orderBy('nama_barang')->get() as $asset)
                            <option value="{{ $asset->id }}"
                                {{ old('asset_id', $repair->asset_id) == $asset->id ? 'selected' : '' }}>
                                {{ $asset->nama_barang }} ({{ $asset->kode_aset }})
                            </option>
                            @endforeach
                        </select>
                        <p class="form-hint">Kaitkan ke aset yang terdaftar di sistem setelah verifikasi</p>
                        @error('asset_id')
                        <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        {{-- ditangani_oleh: ada di DB, tidak tampil ke pelapor.
                             $teknisiList dikirim dari controller hanya untuk Admin Utama.
                             Dokumen: "Petugas perbaikan tidak ditampilkan kepada pengguna pelapor." --}}
                        <label class="form-label">Ditangani Oleh (Teknisi)</label>
                        <select name="ditangani_oleh" class="form-control {{ $errors->has('ditangani_oleh') ? 'is-invalid' : '' }}">
                            <option value="">— Pilih Teknisi —</option>
                            @foreach($teknisiList as $t)
                            <option value="{{ $t->id }}"
                                {{ old('ditangani_oleh', $repair->ditangani_oleh) == $t->id ? 'selected' : '' }}>
                                {{ $t->name }}
                            </option>
                            @endforeach
                        </select>
                        <p class="form-hint">Hanya teknisi dengan status aktif</p>
                        @error('ditangani_oleh')
                        <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Biaya Perbaikan (Rp)</label>
                    <div class="input-wrap">
                        <i class="input-icon fas fa-money-bill-wave"></i>
                        <input type="number" name="biaya_perbaikan" class="form-control"
                            min="0" step="1000"
                            value="{{ old('biaya_perbaikan', $repair->biaya_perbaikan) }}"
                            placeholder="0">
                    </div>
                    <p class="form-hint">Kosongkan jika biaya belum diketahui</p>
                </div>
            </div>

            @endif

            {{-- ── Footer Aksi ── --}}
            <div style="display:flex;gap:10px;justify-content:flex-end;padding-top:4px;">
                <a href="{{ route('repairs.show', $repair) }}" class="btn btn-outline">
                    <i class="fas fa-xmark"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-floppy-disk"></i> Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>

@endsection