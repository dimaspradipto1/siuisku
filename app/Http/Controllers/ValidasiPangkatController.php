<?php

namespace App\Http\Controllers;

use App\DataTables\ValidasiPangkatDataTable;
use App\Http\Requests\ValidasiPangkatActionRequest;
use App\Models\ActivityLog;
use App\Models\KlasifikasiSdm;
use App\Models\RiwayatPangkat;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidasiPangkatController extends Controller
{
    public function index(Request $request, ValidasiPangkatDataTable $dataTable)
    {
        $status = $request->has('status')
            ? ($request->filled('status') ? (string) $request->input('status') : null)
            : RiwayatPangkat::STATUS_DIAJUKAN;

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
            ->render('layouts.pages.validasi-pangkat.index', compact(
                'unitOptions',
                'klasifikasiSdms',
                'status',
                'selectedUnitId',
                'selectedKlasifikasiId',
            ));
    }

    public function show(RiwayatPangkat $riwayatPangkat)
    {
        $riwayatPangkat->load(['pegawai.unitKerja', 'pegawai.klasifikasiSdm', 'pangkat', 'jenisSk', 'validator']);

        return view('layouts.pages.validasi-pangkat.show', compact('riwayatPangkat'));
    }

    public function bulkAction(ValidasiPangkatActionRequest $request)
    {
        $validated = $request->validated();
        $action = $validated['action'];
        $catatan = isset($validated['catatan_validasi']) ? trim($validated['catatan_validasi']) : null;

        $count = RiwayatPangkat::whereIn('id', $validated['ids'])->update([
            'status' => $action,
            'catatan_validasi' => $catatan ?: null,
            'validated_by' => Auth::id(),
            'validated_at' => now(),
        ]);

        $statusLabel = RiwayatPangkat::STATUS_OPTIONS[$action] ?? $action;

        ActivityLog::record(
            Auth::user(),
            'system',
            "Validasi Pangkat ({$statusLabel})",
            "Memperbarui {$count} data riwayat pangkat pegawai menjadi {$statusLabel}.",
            $action === RiwayatPangkat::STATUS_DISETUJUI ? 'success' : 'warning',
        );

        return back()->with('success', "{$count} data riwayat pangkat berhasil diubah statusnya menjadi {$statusLabel}.");
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
