<?php

namespace App\Http\Controllers;

use App\DataTables\RumpunBidangIlmuDataTable;
use App\Http\Requests\RumpunBidangIlmuRequest;
use App\Models\ActivityLog;
use App\Models\RumpunBidangIlmu;
use Illuminate\Support\Facades\Auth;

class RumpunBidangIlmuController extends Controller
{
    public function index(RumpunBidangIlmuDataTable $dataTable)
    {
        $total = RumpunBidangIlmu::count();
        $parentOptions = RumpunBidangIlmu::orderBy('urutan')
            ->orderBy('kode')
            ->get(['id', 'kode', 'nama', 'level']);

        return $dataTable->render(
            'layouts.pages.rumpun-bidang-ilmu.index',
            compact('total', 'parentOptions')
        );
    }

    public function create()
    {
        return redirect()->route('rumpun-bidang-ilmu.index', ['create' => 1]);
    }

    public function store(RumpunBidangIlmuRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);
        $validated['parent_id'] = $validated['parent_id'] ?: null;

        $parent = $validated['parent_id'] ? RumpunBidangIlmu::find($validated['parent_id']) : null;
        $validated['level'] = $parent ? min($parent->level + 1, 3) : 0;
        $validated['urutan'] = ((int) RumpunBidangIlmu::max('urutan')) + 1;

        $item = RumpunBidangIlmu::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Rumpun Bidang Ilmu',
            "Menambahkan {$item->kode} - {$item->nama}.",
            'success',
        );

        return redirect()
            ->route('rumpun-bidang-ilmu.index')
            ->with('success', 'Rumpun bidang ilmu berhasil ditambahkan.');
    }

    public function edit(RumpunBidangIlmu $rumpunBidangIlmu)
    {
        return redirect()->route('rumpun-bidang-ilmu.index');
    }

    public function update(RumpunBidangIlmuRequest $request, RumpunBidangIlmu $rumpunBidangIlmu)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);
        $validated['parent_id'] = $validated['parent_id'] ?: null;

        $parent = $validated['parent_id'] ? RumpunBidangIlmu::find($validated['parent_id']) : null;
        $validated['level'] = $parent ? min($parent->level + 1, 3) : 0;

        $rumpunBidangIlmu->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Rumpun Bidang Ilmu',
            "Memperbarui {$rumpunBidangIlmu->kode} - {$rumpunBidangIlmu->nama}.",
            'info',
        );

        return redirect()
            ->route('rumpun-bidang-ilmu.index')
            ->with('success', 'Rumpun bidang ilmu berhasil diperbarui.');
    }

    public function destroy(RumpunBidangIlmu $rumpunBidangIlmu)
    {
        if ($rumpunBidangIlmu->children()->exists()) {
            return redirect()
                ->route('rumpun-bidang-ilmu.index')
                ->with('error', "Rumpun bidang ilmu ({$rumpunBidangIlmu->kode} - {$rumpunBidangIlmu->nama}) masih memiliki sub-bidang dan tidak dapat dihapus.");
        }

        $label = "{$rumpunBidangIlmu->kode} - {$rumpunBidangIlmu->nama}";
        $rumpunBidangIlmu->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Rumpun Bidang Ilmu',
            "Menghapus {$label}.",
            'warning',
        );

        return redirect()
            ->route('rumpun-bidang-ilmu.index')
            ->with('success', "Rumpun bidang ilmu ({$label}) berhasil dihapus.");
    }
}
