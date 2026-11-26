<?php

namespace App\Http\Controllers;

use App\DataTables\JabatanStrukturalDataTable;
use App\Http\Requests\JabatanStrukturalRequest;
use App\Models\ActivityLog;
use App\Models\JabatanStruktural;
use App\Models\JenisJabatanStruktural;
use App\Models\UnitKerja;
use Illuminate\Support\Facades\Auth;

class JabatanStrukturalController extends Controller
{
    private function formOptions(?int $excludeId = null): array
    {
        $parentsQuery = JabatanStruktural::orderBy('urutan')->orderBy('id');
        if ($excludeId !== null) {
            $parentsQuery->where('id', '!=', $excludeId);
        }

        return [
            'jenisJabatans' => JenisJabatanStruktural::orderBy('urutan')->orderBy('id')->get(),
            'parents' => $parentsQuery->get(),
            'unitKerjas' => UnitKerja::orderBy('urutan')->orderBy('id')->get(),
            'eselonOptions' => [
                'Eselon I' => 'Eselon I',
                'Eselon II' => 'Eselon II',
                'Eselon III' => 'Eselon III',
                'Eselon IV' => 'Eselon IV',
                'Eselon V' => 'Eselon V',
                'Non Eselon' => 'Non Eselon',
            ],
            'pangkatOptions' => [
                'III/a - Penata Muda' => 'III/a - Penata Muda',
                'III/b - Penata Muda Tk. I' => 'III/b - Penata Muda Tk. I',
                'III/c - Penata' => 'III/c - Penata',
                'III/d - Penata Tk. I' => 'III/d - Penata Tk. I',
                'IV/a - Pembina' => 'IV/a - Pembina',
                'IV/b - Pembina Tk. I' => 'IV/b - Pembina Tk. I',
                'IV/c - Pembina Utama Muda' => 'IV/c - Pembina Utama Muda',
                'IV/d - Pembina Utama Madya' => 'IV/d - Pembina Utama Madya',
                'IV/e - Pembina Utama' => 'IV/e - Pembina Utama',
            ],
        ];
    }

    public function index(JabatanStrukturalDataTable $dataTable)
    {
        $total = JabatanStruktural::count();

        return $dataTable->render('layouts.pages.jabatan-struktural.index', compact('total'));
    }

    public function create()
    {
        return view('layouts.pages.jabatan-struktural.create', $this->formOptions());
    }

    public function store(JabatanStrukturalRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['is_pimpinan'] = $request->boolean('is_pimpinan');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['urutan'] = $validated['urutan'] ?? 99;

        $item = JabatanStruktural::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Jabatan Struktural',
            "Menambahkan {$item->kode} - {$item->nama}.",
            'success',
        );

        return redirect()
            ->route('jabatan-struktural.index')
            ->with('success', 'Jabatan struktural berhasil ditambahkan.');
    }

    public function edit(JabatanStruktural $jabatanStruktural)
    {
        return view('layouts.pages.jabatan-struktural.edit', array_merge(
            ['jabatan' => $jabatanStruktural],
            $this->formOptions($jabatanStruktural->id),
        ));
    }

    public function update(JabatanStrukturalRequest $request, JabatanStruktural $jabatanStruktural)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['is_pimpinan'] = $request->boolean('is_pimpinan');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['urutan'] = $validated['urutan'] ?? $jabatanStruktural->urutan;

        $jabatanStruktural->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Jabatan Struktural',
            "Memperbarui {$jabatanStruktural->kode} - {$jabatanStruktural->nama}.",
            'info',
        );

        return redirect()
            ->route('jabatan-struktural.index')
            ->with('success', 'Jabatan struktural berhasil diperbarui.');
    }

    public function destroy(JabatanStruktural $jabatanStruktural)
    {
        $label = "{$jabatanStruktural->kode} - {$jabatanStruktural->nama}";
        $jabatanStruktural->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Jabatan Struktural',
            "Menghapus {$label}.",
            'warning',
        );

        return redirect()
            ->route('jabatan-struktural.index')
            ->with('success', "Jabatan struktural ({$label}) berhasil dihapus.");
    }
}
