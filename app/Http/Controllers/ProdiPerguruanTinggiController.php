<?php

namespace App\Http\Controllers;

use App\DataTables\ProdiPerguruanTinggiDataTable;
use App\Http\Requests\ProdiPerguruanTinggiRequest;
use App\Models\ActivityLog;
use App\Models\JenjangPendidikan;
use App\Models\PerguruanTinggi;
use App\Models\ProdiPerguruanTinggi;
use Illuminate\Support\Facades\Auth;

class ProdiPerguruanTinggiController extends Controller
{
    public function index(ProdiPerguruanTinggiDataTable $dataTable)
    {
        $total = ProdiPerguruanTinggi::count();
        $perguruanTinggis = PerguruanTinggi::query()
            ->orderByRaw("CASE WHEN kode = '101031' THEN 0 WHEN kode = '051024' THEN 1 ELSE 2 END")
            ->orderBy('nama')
            ->get(['id', 'kode', 'nama']);
        $jenjangs = JenjangPendidikan::orderBy('urutan')->orderBy('id')->get(['id', 'kode', 'nama']);
        $defaultPtId = request('perguruan_tinggi_id') ?: ($perguruanTinggis->firstWhere('kode', '101031')?->id ?? '');

        return $dataTable->render(
            'layouts.pages.prodi-perguruan-tinggi.index',
            compact('total', 'perguruanTinggis', 'jenjangs', 'defaultPtId')
        );
    }

    public function create()
    {
        return redirect()->route('prodi-perguruan-tinggi.index', ['create' => 1]);
    }

    public function store(ProdiPerguruanTinggiRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);
        $validated['alamat'] = isset($validated['alamat']) && trim((string) $validated['alamat']) !== ''
            ? trim((string) $validated['alamat'])
            : null;
        $validated['no_telp'] = isset($validated['no_telp']) && trim((string) $validated['no_telp']) !== ''
            ? trim((string) $validated['no_telp'])
            : null;

        $item = ProdiPerguruanTinggi::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Prodi Perguruan Tinggi',
            "Menambahkan {$item->kode} - {$item->nama}.",
            'success',
        );

        return redirect()
            ->route('prodi-perguruan-tinggi.index', ['perguruan_tinggi_id' => $item->perguruan_tinggi_id])
            ->with('success', 'Prodi perguruan tinggi berhasil ditambahkan.');
    }

    public function edit(ProdiPerguruanTinggi $prodiPerguruanTinggi)
    {
        return redirect()->route('prodi-perguruan-tinggi.index');
    }

    public function update(ProdiPerguruanTinggiRequest $request, ProdiPerguruanTinggi $prodiPerguruanTinggi)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);
        $validated['alamat'] = isset($validated['alamat']) && trim((string) $validated['alamat']) !== ''
            ? trim((string) $validated['alamat'])
            : null;
        $validated['no_telp'] = isset($validated['no_telp']) && trim((string) $validated['no_telp']) !== ''
            ? trim((string) $validated['no_telp'])
            : null;

        $prodiPerguruanTinggi->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Prodi Perguruan Tinggi',
            "Memperbarui {$prodiPerguruanTinggi->kode} - {$prodiPerguruanTinggi->nama}.",
            'info',
        );

        return redirect()
            ->route('prodi-perguruan-tinggi.index', ['perguruan_tinggi_id' => $prodiPerguruanTinggi->perguruan_tinggi_id])
            ->with('success', 'Prodi perguruan tinggi berhasil diperbarui.');
    }

    public function destroy(ProdiPerguruanTinggi $prodiPerguruanTinggi)
    {
        $ptId = $prodiPerguruanTinggi->perguruan_tinggi_id;
        $label = "{$prodiPerguruanTinggi->kode} - {$prodiPerguruanTinggi->nama}";
        $prodiPerguruanTinggi->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Prodi Perguruan Tinggi',
            "Menghapus {$label}.",
            'warning',
        );

        return redirect()
            ->route('prodi-perguruan-tinggi.index', ['perguruan_tinggi_id' => $ptId])
            ->with('success', "Prodi perguruan tinggi ({$label}) berhasil dihapus.");
    }
}
