<?php

namespace App\Http\Controllers;

use App\DataTables\JenisHariDataTable;
use App\Http\Requests\JenisHariRequest;
use App\Models\ActivityLog;
use App\Models\JenisHari;
use Illuminate\Support\Facades\Auth;

class JenisHariController extends Controller
{
    public function index(JenisHariDataTable $dataTable)
    {
        $total = JenisHari::count();

        return $dataTable->render('layouts.pages.jenis-hari.index', compact('total'));
    }

    public function update(JenisHariRequest $request, JenisHari $jenisHari)
    {
        $validated = $request->validated();
        $jenisHari->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Setting Hari',
            "Mengubah hari {$jenisHari->nama} menjadi {$jenisHari->jenis_hari}.",
            'info',
        );

        return redirect()
            ->route('jenis-hari.index')
            ->with('success', "Setting hari {$jenisHari->nama} berhasil diperbarui menjadi {$jenisHari->jenis_hari}.");
    }
}
