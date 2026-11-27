<?php

namespace App\Http\Controllers;

use App\Http\Requests\MonitoringValidasiBulkRequest;
use App\Models\ActivityLog;
use App\Models\JenisRiwayat;
use App\Models\Pegawai;
use App\Models\RiwayatPegawai;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class MonitoringValidasiController extends Controller
{
    public function index(Request $request)
    {
        $unitOptions = $this->buildUnitOptions();
        $selectedUnitId = $request->filled('unit_kerja_id')
            ? (int) $request->input('unit_kerja_id')
            : null;
        $selectedPegawaiId = $request->filled('pegawai_id')
            ? (int) $request->input('pegawai_id')
            : null;

        $unitIds = $this->resolveUnitIds($selectedUnitId);

        $pegawais = Pegawai::query()
            ->when($unitIds !== null, fn ($q) => $q->whereIn('unit_kerja_id', $unitIds))
            ->orderBy('nama')
            ->get();

        // Reset selected pegawai if not in selected unit
        if ($selectedPegawaiId && ! $pegawais->contains('id', $selectedPegawaiId)) {
            $selectedPegawaiId = null;
        }

        $countsRaw = RiwayatPegawai::query()
            ->selectRaw('jenis_riwayat_id, status, COUNT(*) as total')
            ->when($unitIds !== null, function ($q) use ($unitIds) {
                $q->whereHas('pegawai', fn ($pq) => $pq->whereIn('unit_kerja_id', $unitIds));
            })
            ->when($selectedPegawaiId, fn ($q) => $q->where('pegawai_id', $selectedPegawaiId))
            ->groupBy('jenis_riwayat_id', 'status')
            ->get();

        $countsMap = [];
        foreach ($countsRaw as $row) {
            $countsMap[$row->jenis_riwayat_id][$row->status] = (int) $row->total;
        }

        $jenisRiwayats = JenisRiwayat::query()
            ->orderBy('urutan_kategori')
            ->orderBy('urutan')
            ->orderBy('id')
            ->get();

        $totals = [
            RiwayatPegawai::STATUS_DRAFT => 0,
            RiwayatPegawai::STATUS_DIAJUKAN => 0,
            RiwayatPegawai::STATUS_DISETUJUI => 0,
            RiwayatPegawai::STATUS_DITOLAK => 0,
            RiwayatPegawai::STATUS_DITANGGUHKAN => 0,
        ];

        $grouped = $jenisRiwayats->groupBy('kategori')->map(function (Collection $items) use ($countsMap, &$totals) {
            return $items->map(function (JenisRiwayat $jenis) use ($countsMap, &$totals) {
                $stats = [
                    RiwayatPegawai::STATUS_DRAFT => $countsMap[$jenis->id][RiwayatPegawai::STATUS_DRAFT] ?? 0,
                    RiwayatPegawai::STATUS_DIAJUKAN => $countsMap[$jenis->id][RiwayatPegawai::STATUS_DIAJUKAN] ?? 0,
                    RiwayatPegawai::STATUS_DISETUJUI => $countsMap[$jenis->id][RiwayatPegawai::STATUS_DISETUJUI] ?? 0,
                    RiwayatPegawai::STATUS_DITOLAK => $countsMap[$jenis->id][RiwayatPegawai::STATUS_DITOLAK] ?? 0,
                    RiwayatPegawai::STATUS_DITANGGUHKAN => $countsMap[$jenis->id][RiwayatPegawai::STATUS_DITANGGUHKAN] ?? 0,
                ];

                foreach ($totals as $status => $val) {
                    $totals[$status] += $stats[$status];
                }

                return [
                    'jenis' => $jenis,
                    'stats' => $stats,
                ];
            });
        });

        return view('layouts.pages.monitoring-validasi.index', compact(
            'unitOptions',
            'selectedUnitId',
            'pegawais',
            'selectedPegawaiId',
            'grouped',
            'totals',
        ));
    }

    public function show(Request $request, JenisRiwayat $jenisRiwayat)
    {
        $selectedUnitId = $request->filled('unit_kerja_id') ? (int) $request->input('unit_kerja_id') : null;
        $selectedPegawaiId = $request->filled('pegawai_id') ? (int) $request->input('pegawai_id') : null;
        $selectedStatus = $request->filled('status') ? (string) $request->input('status') : null;

        $unitIds = $this->resolveUnitIds($selectedUnitId);

        $riwayats = RiwayatPegawai::query()
            ->with(['pegawai.unitKerja', 'validator'])
            ->where('jenis_riwayat_id', $jenisRiwayat->id)
            ->when($unitIds !== null, function ($q) use ($unitIds) {
                $q->whereHas('pegawai', fn ($pq) => $pq->whereIn('unit_kerja_id', $unitIds));
            })
            ->when($selectedPegawaiId, fn ($q) => $q->where('pegawai_id', $selectedPegawaiId))
            ->when($selectedStatus, fn ($q) => $q->where('status', $selectedStatus))
            ->orderByRaw("CASE WHEN status = 'diajukan' THEN 0 ELSE 1 END")
            ->orderByDesc('tanggal_pengajuan')
            ->orderByDesc('id')
            ->get();

        $selectedUnit = $selectedUnitId ? UnitKerja::find($selectedUnitId) : null;
        $selectedPegawai = $selectedPegawaiId ? Pegawai::find($selectedPegawaiId) : null;

        return view('layouts.pages.monitoring-validasi.show', compact(
            'jenisRiwayat',
            'riwayats',
            'selectedUnit',
            'selectedPegawai',
            'selectedUnitId',
            'selectedPegawaiId',
            'selectedStatus',
        ));
    }

    public function bulkAction(MonitoringValidasiBulkRequest $request)
    {
        $validated = $request->validated();
        $action = $validated['action'];
        $catatan = isset($validated['catatan_validasi']) ? trim($validated['catatan_validasi']) : null;

        $query = RiwayatPegawai::query();

        if (! empty($validated['riwayat_ids'])) {
            $query->whereIn('id', $validated['riwayat_ids']);
        } elseif (! empty($validated['jenis_riwayat_ids'])) {
            $unitIds = $this->resolveUnitIds($validated['unit_kerja_id'] ?? null);

            $query->whereIn('jenis_riwayat_id', $validated['jenis_riwayat_ids'])
                ->where('status', RiwayatPegawai::STATUS_DIAJUKAN)
                ->when($unitIds !== null, function ($q) use ($unitIds) {
                    $q->whereHas('pegawai', fn ($pq) => $pq->whereIn('unit_kerja_id', $unitIds));
                })
                ->when(! empty($validated['pegawai_id']), fn ($q) => $q->where('pegawai_id', $validated['pegawai_id']));
        } else {
            return back()->with('error', 'Pilih minimal satu data riwayat terlebih dahulu.');
        }

        $count = $query->update([
            'status' => $action,
            'catatan_validasi' => $catatan ?: null,
            'validated_by' => Auth::id(),
            'validated_at' => now(),
        ]);

        if ($count === 0) {
            return back()->with('error', 'Tidak ada data riwayat berstatus Diajukan yang dapat diproses.');
        }

        $statusLabel = RiwayatPegawai::STATUSES[$action] ?? $action;

        ActivityLog::record(
            Auth::user(),
            'system',
            "Validasi Riwayat ({$statusLabel})",
            "Memperbarui {$count} data riwayat pegawai menjadi {$statusLabel}.",
            $action === RiwayatPegawai::STATUS_DISETUJUI ? 'success' : 'warning',
        );

        return back()->with('success', "{$count} data riwayat berhasil diubah statusnya menjadi {$statusLabel}.");
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
                $prefix = str_repeat('— ', $depth);
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

    /**
     * @return array<int, int>|null
     */
    private function resolveUnitIds(?int $unitKerjaId): ?array
    {
        if (! $unitKerjaId) {
            return null;
        }

        $unit = UnitKerja::with('children.children')->find($unitKerjaId);

        return $unit ? $unit->descendantIds() : [$unitKerjaId];
    }
}
