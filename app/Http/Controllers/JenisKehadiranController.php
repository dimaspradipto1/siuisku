<?php

namespace App\Http\Controllers;

use App\DataTables\JenisKehadiranDataTable;
use App\Http\Requests\JenisKehadiranRequest;
use App\Models\ActivityLog;
use App\Models\JenisKehadiran;
use Illuminate\Support\Facades\Auth;

class JenisKehadiranController extends Controller
{
    public function index(JenisKehadiranDataTable $dataTable)
    {
        $total = JenisKehadiran::count();

        return $dataTable->render('layouts.pages.jenis-kehadiran.index', compact('total'));
    }

    public function create()
    {
        return redirect()->route('jenis-kehadiran.index', ['create' => 1]);
    }

    public function store(JenisKehadiranRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);
        $validated['warna'] = $this->normalizeHex($validated['warna'] ?? null);

        $item = JenisKehadiran::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Jenis Kehadiran',
            "Menambahkan {$item->kode} - {$item->nama}.",
            'success',
        );

        return redirect()
            ->route('jenis-kehadiran.index')
            ->with('success', 'Jenis kehadiran berhasil ditambahkan.');
    }

    public function edit(JenisKehadiran $jenisKehadiran)
    {
        return redirect()->route('jenis-kehadiran.index');
    }

    public function update(JenisKehadiranRequest $request, JenisKehadiran $jenisKehadiran)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);
        $validated['warna'] = $this->normalizeHex($validated['warna'] ?? null);

        $jenisKehadiran->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Jenis Kehadiran',
            "Memperbarui {$jenisKehadiran->kode} - {$jenisKehadiran->nama}.",
            'info',
        );

        return redirect()
            ->route('jenis-kehadiran.index')
            ->with('success', 'Jenis kehadiran berhasil diperbarui.');
    }

    public function destroy(JenisKehadiran $jenisKehadiran)
    {
        $label = "{$jenisKehadiran->kode} - {$jenisKehadiran->nama}";
        $jenisKehadiran->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Jenis Kehadiran',
            "Menghapus {$label}.",
            'warning',
        );

        return redirect()
            ->route('jenis-kehadiran.index')
            ->with('success', "Jenis kehadiran ({$label}) berhasil dihapus.");
    }

    private function normalizeHex(?string $value): ?string
    {
        $trimmed = trim((string) $value);
        if ($trimmed === '') {
            return null;
        }

        return strtoupper(str_starts_with($trimmed, '#') ? $trimmed : '#' . $trimmed);
    }
}
