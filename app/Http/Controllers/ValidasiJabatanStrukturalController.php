<?php

namespace App\Http\Controllers;

use App\DataTables\ValidasiJabatanStrukturalDataTable;
use App\Http\Requests\ValidasiJabatanStrukturalActionRequest;
use App\Models\ActivityLog;
use App\Models\JabatanStruktural;
use App\Models\RiwayatJabatanStruktural;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidasiJabatanStrukturalController extends Controller
{
    public function index(Request $request, ValidasiJabatanStrukturalDataTable $dataTable)
    {
        $status = $request->has('status')
            ? ($request->filled('status') ? (string) $request->input('status') : null)
            : RiwayatJabatanStruktural::STATUS_DIAJUKAN;

        $selectedUnitId = $request->filled('unit_kerja_id')
            ? (int) $request->input('unit_kerja_id')
            : null;
        $selectedJabatanId = $request->filled('jabatan_struktural_id')
            ? (int) $request->input('jabatan_struktural_id')
            : null;

        $unitOptions = $this->buildUnitOptions();
        $jabatanStrukturals = JabatanStruktural::orderBy('urutan')->orderBy('nama')->get();

        return $dataTable
            ->withFilters($status, $selectedUnitId, $selectedJabatanId)
            ->render('layouts.pages.validasi-jabatan-struktural.index', compact(
                'unitOptions',
                'jabatanStrukturals',
                'status',
                'selectedUnitId',
                'selectedJabatanId',
            ));
    }

    public function show(RiwayatJabatanStruktural $riwayatJabatanStruktural)
    {
        $riwayatJabatanStruktural->load([
            'pegawai.unitKerja',
            'jabatanStruktural.unitKerja',
            'validator',
        ]);

        return view('layouts.pages.validasi-jabatan-struktural.show', compact('riwayatJabatanStruktural'));
    }

    public function bulkAction(ValidasiJabatanStrukturalActionRequest $request)
    {
        $validated = $request->validated();
        $action = $validated['action'];
        $catatan = isset($validated['catatan_validasi']) ? trim($validated['catatan_validasi']) : null;

        $count = RiwayatJabatanStruktural::whereIn('id', $validated['ids'])->update([
            'status' => $action,
            'catatan_validasi' => $catatan ?: null,
            'validated_by' => Auth::id(),
            'validated_at' => now(),
        ]);

        $statusLabel = RiwayatJabatanStruktural::STATUS_OPTIONS[$action] ?? $action;

        ActivityLog::record(
            Auth::user(),
            'system',
            "Validasi Jabatan Struktural ({$statusLabel})",
            "Memperbarui {$count} data riwayat jabatan struktural pegawai menjadi {$statusLabel}.",
            $action === RiwayatJabatanStruktural::STATUS_DISETUJUI ? 'success' : 'warning',
        );

        return back()->with('success', "{$count} data riwayat jabatan struktural berhasil diubah statusnya menjadi {$statusLabel}.");
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
