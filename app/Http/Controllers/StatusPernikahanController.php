<?php

namespace App\Http\Controllers;

use App\DataTables\StatusPernikahanDataTable;
use App\Http\Requests\StatusPernikahanRequest;
use App\Models\ActivityLog;
use App\Models\StatusPernikahan;
use Illuminate\Support\Facades\Auth;

class StatusPernikahanController extends Controller
{
    public function index(StatusPernikahanDataTable $dataTable)
    {
        $total = StatusPernikahan::count();

        return $dataTable->render('layouts.pages.status-pernikahan.index', compact('total'));
    }

    public function update(StatusPernikahanRequest $request, StatusPernikahan $statusPernikahan)
    {
        $validated = $request->validated();
        $validated['nama'] = trim($validated['nama']);

        $statusPernikahan->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Status Pernikahan',
            "Memperbarui {$statusPernikahan->kode} - {$statusPernikahan->nama}.",
            'info',
        );

        return redirect()
            ->route('status-pernikahan.index')
            ->with('success', 'Status pernikahan berhasil diperbarui.');
    }
}
