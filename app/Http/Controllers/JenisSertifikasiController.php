<?php

namespace App\Http\Controllers;

use App\DataTables\JenisSertifikasiDataTable;
use App\Http\Requests\JenisSertifikasiRequest;
use App\Models\ActivityLog;
use App\Models\JenisSertifikasi;
use Illuminate\Support\Facades\Auth;

class JenisSertifikasiController extends Controller
{
    public function index(JenisSertifikasiDataTable $dataTable)
    {
        $total = JenisSertifikasi::count();
        $kategoriOptions = JenisSertifikasi::KATEGORI_OPTIONS;

        return $dataTable->render(
            'layouts.pages.jenis-sertifikasi.index',
            compact('total', 'kategoriOptions')
        );
    }

    public function create()
    {
        return redirect()->route('jenis-sertifikasi.index', ['create' => 1]);
    }

    public function store(JenisSertifikasiRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);
        $validated['is_system'] = false;

        $item = JenisSertifikasi::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Jenis Sertifikasi',
            "Menambahkan {$item->kode} - {$item->nama} ({$item->kategori}).",
            'success',
        );

        return redirect()
            ->route('jenis-sertifikasi.index')
            ->with('success', 'Jenis sertifikasi berhasil ditambahkan.');
    }

    public function edit(JenisSertifikasi $jenisSertifikasi)
    {
        return redirect()->route('jenis-sertifikasi.index');
    }

    public function update(JenisSertifikasiRequest $request, JenisSertifikasi $jenisSertifikasi)
    {
        if ($jenisSertifikasi->is_system) {
            return redirect()
                ->route('jenis-sertifikasi.index')
                ->with('error', "Jenis sertifikasi sistem ({$jenisSertifikasi->kode} - {$jenisSertifikasi->nama}) tidak dapat diubah.");
        }

        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);

        $jenisSertifikasi->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Jenis Sertifikasi',
            "Memperbarui {$jenisSertifikasi->kode} - {$jenisSertifikasi->nama} ({$jenisSertifikasi->kategori}).",
            'info',
        );

        return redirect()
            ->route('jenis-sertifikasi.index')
            ->with('success', 'Jenis sertifikasi berhasil diperbarui.');
    }

    public function destroy(JenisSertifikasi $jenisSertifikasi)
    {
        if ($jenisSertifikasi->is_system) {
            return redirect()
                ->route('jenis-sertifikasi.index')
                ->with('error', "Jenis sertifikasi sistem ({$jenisSertifikasi->kode} - {$jenisSertifikasi->nama}) tidak dapat dihapus.");
        }

        $label = "{$jenisSertifikasi->kode} - {$jenisSertifikasi->nama}";
        $jenisSertifikasi->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Jenis Sertifikasi',
            "Menghapus {$label}.",
            'warning',
        );

        return redirect()
            ->route('jenis-sertifikasi.index')
            ->with('success', "Jenis sertifikasi ({$label}) berhasil dihapus.");
    }
}
