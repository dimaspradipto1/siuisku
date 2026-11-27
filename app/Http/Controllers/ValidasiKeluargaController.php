<?php

namespace App\Http\Controllers;

use App\DataTables\ValidasiKeluargaDataTable;
use App\Http\Requests\ValidasiKeluargaActionRequest;
use App\Models\ActivityLog;
use App\Models\KeluargaPegawai;
use App\Models\KlasifikasiSdm;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidasiKeluargaController extends Controller
{
    public function index(Request $request, ValidasiKeluargaDataTable $dataTable)
    {
        $hubungan = $request->input('hubungan', KeluargaPegawai::HUBUNGAN_PASANGAN);
        if (! array_key_exists($hubungan, KeluargaPegawai::HUBUNGAN_OPTIONS)) {
            $hubungan = KeluargaPegawai::HUBUNGAN_PASANGAN;
        }

        $status = $request->has('status')
            ? ($request->filled('status') ? (string) $request->input('status') : null)
            : KeluargaPegawai::STATUS_DIAJUKAN;

        $selectedUnitId = $request->filled('unit_kerja_id')
            ? (int) $request->input('unit_kerja_id')
            : null;
        $selectedKlasifikasiId = $request->filled('klasifikasi_sdm_id')
            ? (int) $request->input('klasifikasi_sdm_id')
            : null;

        $unitOptions = $this->buildUnitOptions();
        $klasifikasiSdms = KlasifikasiSdm::orderBy('id')->get();
        $hubunganLabel = KeluargaPegawai::HUBUNGAN_OPTIONS[$hubungan];

        return $dataTable
            ->withFilters($hubungan, $status, $selectedUnitId, $selectedKlasifikasiId)
            ->render('layouts.pages.validasi-keluarga.index', compact(
                'unitOptions',
                'klasifikasiSdms',
                'hubungan',
                'hubunganLabel',
                'status',
                'selectedUnitId',
                'selectedKlasifikasiId',
            ));
    }

    public function show(KeluargaPegawai $keluargaPegawai)
    {
        $keluargaPegawai->load(['pegawai.unitKerja', 'pegawai.klasifikasiSdm', 'validator']);

        return view('layouts.pages.validasi-keluarga.show', compact('keluargaPegawai'));
    }

    public function bulkAction(ValidasiKeluargaActionRequest $request)
    {
        $validated = $request->validated();
        $action = $validated['action'];
        $catatan = isset($validated['catatan_validasi']) ? trim($validated['catatan_validasi']) : null;

        $count = KeluargaPegawai::whereIn('id', $validated['ids'])->update([
            'status' => $action,
            'catatan_validasi' => $catatan ?: null,
            'validated_by' => Auth::id(),
            'validated_at' => now(),
        ]);

        $statusLabel = KeluargaPegawai::STATUS_OPTIONS[$action] ?? $action;

        ActivityLog::record(
            Auth::user(),
            'system',
            "Validasi Keluarga ({$statusLabel})",
            "Memperbarui {$count} data keluarga pegawai menjadi {$statusLabel}.",
            $action === KeluargaPegawai::STATUS_DISETUJUI ? 'success' : 'warning',
        );

        return back()->with('success', "{$count} data keluarga berhasil diubah statusnya menjadi {$statusLabel}.");
    }

    /**
     * @return array<int, array{id: int, label: string}>
     */
    private function buildUnitOptions(): array
    {
        $all = UnitKerja::query()
            ->orderBy('urutan')
            ->orderBy('id')
            ->get()
            ->groupBy('parent_id');

        $options = [];
        $walk = function ($parentId, int $depth) use (&$walk, $all, &$options): void {
            foreach ($all->get($parentId, collect()) as $unit) {
                $prefix = str_repeat("\u{00A0}", $depth * 4);
                $options[] = [
                    'id' => $unit->id,
                    'label' => "{$prefix}{$unit->kode} - {$unit->nama}",
                ];
                $walk($unit->id, $depth + 1);
            }
        };

        $walk(null, 0);

        return $options;
    }
}
