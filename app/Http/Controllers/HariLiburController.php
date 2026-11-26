<?php

namespace App\Http\Controllers;

use App\DataTables\HariLiburDataTable;
use App\Http\Requests\HariLiburRequest;
use App\Models\ActivityLog;
use App\Models\HariLibur;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class HariLiburController extends Controller
{
    public function index(HariLiburDataTable $dataTable)
    {
        $total = HariLibur::count();
        $currentYear = (int) now()->year;

        $dbYears = HariLibur::query()
            ->pluck('tanggal_mulai')
            ->filter()
            ->map(fn ($date) => (int) Carbon::parse($date)->year)
            ->unique()
            ->all();

        $defaultRange = range($currentYear - 1, $currentYear + 2);
        $tahunOptions = collect(array_merge($dbYears, $defaultRange))
            ->unique()
            ->sortDesc()
            ->values();

        $defaultTahun = request('tahun', $currentYear);

        return $dataTable->render(
            'layouts.pages.hari-libur.index',
            compact('total', 'tahunOptions', 'defaultTahun')
        );
    }

    public function create()
    {
        return redirect()->route('hari-libur.index', ['create' => 1]);
    }

    public function store(HariLiburRequest $request)
    {
        $validated = $request->validated();
        $validated['nama'] = trim($validated['nama']);
        $validated['tanggal_selesai'] = $validated['tanggal_selesai'] ?: null;
        $validated['keterangan'] = isset($validated['keterangan']) && trim((string) $validated['keterangan']) !== ''
            ? trim((string) $validated['keterangan'])
            : null;

        $item = HariLibur::create($validated);
        $tahun = $item->tanggal_mulai->year;

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Hari Libur',
            "Menambahkan {$item->nama} ({$item->tanggal_mulai->format('d-m-Y')}).",
            'success',
        );

        return redirect()
            ->route('hari-libur.index', ['tahun' => $tahun])
            ->with('success', 'Hari libur berhasil ditambahkan.');
    }

    public function edit(HariLibur $hariLibur)
    {
        return redirect()->route('hari-libur.index');
    }

    public function update(HariLiburRequest $request, HariLibur $hariLibur)
    {
        $validated = $request->validated();
        $validated['nama'] = trim($validated['nama']);
        $validated['tanggal_selesai'] = $validated['tanggal_selesai'] ?: null;
        $validated['keterangan'] = isset($validated['keterangan']) && trim((string) $validated['keterangan']) !== ''
            ? trim((string) $validated['keterangan'])
            : null;

        $hariLibur->update($validated);
        $tahun = $hariLibur->tanggal_mulai->year;

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Hari Libur',
            "Memperbarui {$hariLibur->nama} ({$hariLibur->tanggal_mulai->format('d-m-Y')}).",
            'info',
        );

        return redirect()
            ->route('hari-libur.index', ['tahun' => $tahun])
            ->with('success', 'Hari libur berhasil diperbarui.');
    }

    public function destroy(HariLibur $hariLibur)
    {
        $tahun = $hariLibur->tanggal_mulai->year;
        $label = $hariLibur->nama;
        $hariLibur->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Hari Libur',
            "Menghapus {$label}.",
            'warning',
        );

        return redirect()
            ->route('hari-libur.index', ['tahun' => $tahun])
            ->with('success', "Hari libur ({$label}) berhasil dihapus.");
    }
}
