<?php

namespace App\Http\Controllers;

use App\DataTables\ValidasiHubunganKerjaDataTable;
use App\Http\Requests\ValidasiHubunganKerjaActionRequest;
use App\Models\ActivityLog;
use App\Models\KlasifikasiSdm;
use App\Models\RiwayatHubunganKerja;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidasiHubunganKerjaController extends Controller
{
    public function index(Request $request, ValidasiHubunganKerjaDataTable $dataTable)
    {
        $status = $request->has('status')
            ? ($request->filled('status') ? (string) $request->input('status') : null)
            : RiwayatHubunganKerja::STATUS_DIAJUKAN;

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
            ->render('layouts.pages.validasi-hubungan-kerja.index', compact(
                'unitOptions',
                'klasifikasiSdms',
                'status',
                'selectedUnitId',
                'selectedKlasifikasiId',
            ));
    }

    public function show(RiwayatHubunganKerja $riwayatHubunganKerja)
    {
        $riwayatHubunganKerja->load([
            'pegawai.unitKerja',
            'pegawai.klasifikasiSdm',
            'hubunganKerja',
            'validator',
        ]);

        return view('layouts.pages.validasi-hubungan-kerja.show', compact('riwayatHubunganKerja'));
    }

    public function bulkAction(ValidasiHubunganKerjaActionRequest $request)
    {
        $validated = $request->validated();
        $action = $validated['action'];
        $catatan = isset($validated['catatan_validasi']) ? trim($validated['catatan_validasi']) : null;

        $count = RiwayatHubunganKerja::whereIn('id', $validated['ids'])->update([
            'status' => $action,
            'catatan_validasi' => $catatan ?: null,
            'validated_by' => Auth::id(),
            'validated_at' => now(),
        ]);

        $statusLabel = RiwayatHubunganKerja::STATUS_OPTIONS[$action] ?? $action;

        ActivityLog::record(
            Auth::user(),
            'system',
            "Validasi Hubungan Kerja ({$statusLabel})",
            "Memperbarui {$count} data riwayat hubungan kerja pegawai menjadi {$statusLabel}.",
            $action === RiwayatHubunganKerja::STATUS_DISETUJUI ? 'success' : 'warning',
        );

        return back()->with('success', "{$count} data riwayat hubungan kerja berhasil diubah statusnya menjadi {$statusLabel}.");
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
