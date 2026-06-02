<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\FundingSource;
use App\Models\UnitSatuan;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MasterDataController extends Controller
{
    private function authorizeAdmin(): void
    {
        if (!Auth::user()->isAdminUtama()) abort(403);
    }

    public function index()
    {
        $this->authorizeAdmin();

        $satuanList     = UnitSatuan::orderBy('nama_satuan')->get();
        $fundingSources = FundingSource::orderBy('nama_sumber')->get();
        $units          = Unit::orderBy('nama_unit')->get();

        return view('master-data.index', compact(
            'satuanList', 'fundingSources', 'units',
        ));
    }

    public function storeUnit(Request $request)
    {
        $this->authorizeAdmin();
        $validated = $request->validate([
            'nama_unit'  => 'required|string|max:255|unique:units,nama_unit',
            'kode_unit'  => 'required|string|max:20|unique:units,kode_unit|regex:/^[A-Za-z0-9]+$/',
            'deskripsi'  => 'nullable|string',
            'is_yayasan' => 'boolean',
        ], [
            'nama_unit.unique' => 'Nama unit sudah digunakan.',
            'kode_unit.unique' => 'Kode unit sudah digunakan.',
            'kode_unit.regex'  => 'Kode unit hanya boleh berisi huruf dan angka.',
        ]);
        $unit = Unit::create(array_merge($validated, [
            'is_yayasan' => $request->boolean('is_yayasan'),
            'is_active'  => true,
        ]));
        ActivityLog::record(
            action: 'tambah_unit', subject: $unit,
            description: "Menambahkan unit {$unit->nama_unit} (kode: {$unit->kode_unit})",
            newData: $unit->only(['nama_unit', 'kode_unit', 'is_yayasan']),
        );
        return redirect()->route('masterdata.index')
            ->with('success', "Unit {$unit->nama_unit} berhasil ditambahkan.");
    }

    public function updateUnit(Request $request, Unit $unit)
    {
        $this->authorizeAdmin();
        $validated = $request->validate([
            'nama_unit' => 'required|string|max:255|unique:units,nama_unit,' . $unit->id,
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean',
        ], ['nama_unit.unique' => 'Nama unit sudah digunakan.']);
        $oldData = $unit->only(['nama_unit', 'deskripsi', 'is_active']);
        $unit->update([
            'nama_unit' => $validated['nama_unit'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);
        ActivityLog::record(
            action: 'edit_unit', subject: $unit,
            description: "Memperbarui unit {$unit->nama_unit}",
            oldData: $oldData,
            newData: $unit->fresh()->only(['nama_unit', 'deskripsi', 'is_active']),
        );
        return redirect()->route('masterdata.index')
            ->with('success', "Unit {$unit->nama_unit} berhasil diperbarui.");
    }

    public function destroyUnit(Unit $unit)
    {
        abort(403, 'Unit tidak dapat dihapus. Nonaktifkan unit melalui menu edit.');
    }

    public function storeFundingSource(Request $request)
    {
        $this->authorizeAdmin();
        $validated = $request->validate([
            'nama_sumber' => 'required|string|max:255|unique:funding_sources,nama_sumber',
            'deskripsi'   => 'nullable|string',
        ], ['nama_sumber.unique' => 'Nama sumber dana sudah digunakan.']);
        $fundingSource = FundingSource::create(array_merge($validated, ['is_active' => true]));
        ActivityLog::record(
            action: 'tambah_sumber_dana', subject: $fundingSource,
            description: "Menambahkan sumber dana: {$fundingSource->nama_sumber}",
            newData: $fundingSource->only(['nama_sumber', 'deskripsi']),
        );
        return redirect()->route('masterdata.index')
            ->with('success', "Sumber dana {$fundingSource->nama_sumber} berhasil ditambahkan.");
    }

    public function updateFundingSource(Request $request, FundingSource $fundingSource)
    {
        $this->authorizeAdmin();
        $validated = $request->validate([
            'nama_sumber' => 'required|string|max:255|unique:funding_sources,nama_sumber,' . $fundingSource->id,
            'deskripsi'   => 'nullable|string',
            'is_active'   => 'boolean',
        ], ['nama_sumber.unique' => 'Nama sumber dana sudah digunakan.']);
        $oldData = $fundingSource->only(['nama_sumber', 'deskripsi', 'is_active']);
        $fundingSource->update([
            'nama_sumber' => $validated['nama_sumber'],
            'deskripsi'   => $validated['deskripsi'] ?? null,
            'is_active'   => $request->boolean('is_active'),
        ]);
        ActivityLog::record(
            action: 'edit_sumber_dana', subject: $fundingSource,
            description: "Memperbarui sumber dana: {$fundingSource->nama_sumber}",
            oldData: $oldData,
            newData: $fundingSource->fresh()->only(['nama_sumber', 'deskripsi', 'is_active']),
        );
        return redirect()->route('masterdata.index')
            ->with('success', "Sumber dana {$fundingSource->nama_sumber} berhasil diperbarui.");
    }

    public function destroyFundingSource(FundingSource $fundingSource)
    {
        abort(403, 'Sumber dana tidak dapat dihapus. Nonaktifkan melalui menu edit.');
    }

    public function storeWarehouseType(Request $request)
    {
        $this->authorizeAdmin();
        $validated = $request->validate([
            'nama_jenis' => 'required|string|max:255|unique:warehouse_types,nama_jenis',
            'deskripsi'  => 'nullable|string',
        ], ['nama_jenis.unique' => 'Nama jenis gudang sudah digunakan.']);
        $warehouseType = WarehouseType::create(array_merge($validated, ['is_active' => true]));
        ActivityLog::record(
            action: 'tambah_jenis_gudang', subject: $warehouseType,
            description: "Menambahkan jenis gudang: {$warehouseType->nama_jenis}",
            newData: $warehouseType->only(['nama_jenis', 'deskripsi']),
        );
        return redirect()->route('masterdata.index')
            ->with('success', "Jenis gudang {$warehouseType->nama_jenis} berhasil ditambahkan.");
    }

    public function updateWarehouseType(Request $request, WarehouseType $warehouseType)
    {
        $this->authorizeAdmin();
        $validated = $request->validate([
            'nama_jenis' => 'required|string|max:255|unique:warehouse_types,nama_jenis,' . $warehouseType->id,
            'deskripsi'  => 'nullable|string',
            'is_active'  => 'boolean',
        ], ['nama_jenis.unique' => 'Nama jenis gudang sudah digunakan.']);
        $oldData = $warehouseType->only(['nama_jenis', 'deskripsi', 'is_active']);
        $warehouseType->update([
            'nama_jenis' => $validated['nama_jenis'],
            'deskripsi'  => $validated['deskripsi'] ?? null,
            'is_active'  => $request->boolean('is_active'),
        ]);
        ActivityLog::record(
            action: 'edit_jenis_gudang', subject: $warehouseType,
            description: "Memperbarui jenis gudang: {$warehouseType->nama_jenis}",
            oldData: $oldData,
            newData: $warehouseType->fresh()->only(['nama_jenis', 'deskripsi', 'is_active']),
        );
        return redirect()->route('masterdata.index')
            ->with('success', "Jenis gudang {$warehouseType->nama_jenis} berhasil diperbarui.");
    }

    public function destroyWarehouseType(WarehouseType $warehouseType)
    {
        abort(403, 'Jenis gudang tidak dapat dihapus. Nonaktifkan melalui menu edit.');
    }
}