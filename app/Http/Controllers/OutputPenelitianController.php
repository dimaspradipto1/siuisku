<?php

namespace App\Http\Controllers;

use App\DataTables\OutputPenelitianDataTable;
use App\Http\Requests\OutputPenelitianRequest;
use App\Models\ActivityLog;
use App\Models\OutputPenelitian;
use Illuminate\Support\Facades\Auth;

class OutputPenelitianController extends Controller
{
    public function index(OutputPenelitianDataTable $dataTable)
    {
        $total = OutputPenelitian::count();

        return $dataTable->render('layouts.pages.output-penelitian.index', compact('total'));
    }

    public function create()
    {
        return redirect()->route('output-penelitian.index', ['create' => 1]);
    }

    public function store(OutputPenelitianRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);

        $item = OutputPenelitian::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Output Penelitian',
            "Menambahkan {$item->kode} - {$item->nama}.",
            'success',
        );

        return redirect()
            ->route('output-penelitian.index')
            ->with('success', 'Output penelitian berhasil ditambahkan.');
    }

    public function edit(OutputPenelitian $outputPenelitian)
    {
        return redirect()->route('output-penelitian.index');
    }

    public function update(OutputPenelitianRequest $request, OutputPenelitian $outputPenelitian)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);

        $outputPenelitian->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Output Penelitian',
            "Memperbarui {$outputPenelitian->kode} - {$outputPenelitian->nama}.",
            'info',
        );

        return redirect()
            ->route('output-penelitian.index')
            ->with('success', 'Output penelitian berhasil diperbarui.');
    }

    public function destroy(OutputPenelitian $outputPenelitian)
    {
        $label = "{$outputPenelitian->kode} - {$outputPenelitian->nama}";
        $outputPenelitian->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Output Penelitian',
            "Menghapus {$label}.",
            'warning',
        );

        return redirect()
            ->route('output-penelitian.index')
            ->with('success', "Output penelitian ({$label}) berhasil dihapus.");
    }
}
