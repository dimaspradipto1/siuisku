<?php

namespace App\Http\Controllers;

use App\DataTables\ValidasiRiwayatPekerjaanDataTable;
use App\Http\Requests\ValidasiRiwayatPekerjaanActionRequest;
use App\Models\ActivityLog;
use App\Models\KlasifikasiSdm;
use App\Models\RiwayatPekerjaan;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidasiRiwayatPekerjaanController extends Controller
{
    public function index(Request $request, ValidasiRiwayatPekerjaanDataTable $dataTable)
    {
        $status = $request->has('status')
            ? ($request->filled('status') ? (string) $request->input('status') : null)
            : RiwayatPekerjaan::STATUS_DIAJUKAN;

        $selectedUnitId = $request->filled('unit_kerja_id')
            ? (int) $request->input('unit_kerja_id')
            : null;
        $selectedKlasifikasiId = $request->filled('klasifikasi_sdm_id')
            ? (int) $request->input('klasifikasi_sdm_id')
            : null;

        $unitOptions = $this->buildUnitOptions();
        $klasifikasiSdms = KlasifikasiSdm::orderBy('id')->get();

        return $dataTable
            ->withFilters($status, $selectedUnitId, $selectedKlasifikasiId)
            ->render('layouts.pages.validasi-riwayat-pekerjaan.index', compact(
                'unitOptions',
                'klasifikasiSdms',
                'status',
                'selectedUnitId',
                'selectedKlasifikasiId',
            ));
    }

    public function show(RiwayatPekerjaan $riwayatPekerjaan)
    {
        $riwayatPekerjaan->load([
            'pegawai.unitKerja',
            'pegawai.klasifikasiSdm',
            'pekerjaan',
            'validator',
        ]);

        return view('layouts.pages.validasi-riwayat-pekerjaan.show', compact('riwayatPekerjaan'));
    }

    public function bulkAction(ValidasiRiwayatPekerjaanActionRequest $request)
    {
        $validated = $request->validated();
        $action = $validated['action'];
        $catatan = isset($validated['catatan_validasi']) ? trim($validated['catatan_validasi']) : null;

        $count = RiwayatPekerjaan::whereIn('id', $validated['ids'])->update([
            'status' => $action,
            'catatan_validasi' => $catatan ?: null,
            'validated_by' => Auth::id(),
            'validated_at' => now(),
        ]);

        $statusLabel = RiwayatPekerjaan::STATUS_OPTIONS[$action] ?? $action;

        ActivityLog::record(
            Auth::user(),
            'system',
            "Validasi Riwayat Pekerjaan ({$statusLabel})",
            "Memperbarui {$count} data riwayat pekerjaan pegawai menjadi {$statusLabel}.",
            $action === RiwayatPekerjaan::STATUS_DISETUJUI ? 'success' : 'warning',
        );

        return back()->with('success', "{$count} data riwayat pekerjaan berhasil diubah statusnya menjadi {$statusLabel}.");
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
