<?php

namespace App\Http\Controllers;

use App\DataTables\BankDataTable;
use App\Http\Requests\BankRequest;
use App\Models\ActivityLog;
use App\Models\Bank;
use Illuminate\Support\Facades\Auth;

class BankController extends Controller
{
    public function index(BankDataTable $dataTable)
    {
        $total = Bank::count();

        return $dataTable->render('layouts.pages.bank.index', compact('total'));
    }

    public function create()
    {
        return redirect()->route('bank.index', ['create' => 1]);
    }

    public function store(BankRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);

        $item = Bank::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Bank',
            "Menambahkan {$item->kode} - {$item->nama}.",
            'success',
        );

        return redirect()
            ->route('bank.index')
            ->with('success', 'Bank berhasil ditambahkan.');
    }

    public function edit(Bank $bank)
    {
        return redirect()->route('bank.index');
    }

    public function update(BankRequest $request, Bank $bank)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);

        $bank->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Bank',
            "Memperbarui {$bank->kode} - {$bank->nama}.",
            'info',
        );

        return redirect()
            ->route('bank.index')
            ->with('success', 'Bank berhasil diperbarui.');
    }

    public function destroy(Bank $bank)
    {
        $label = "{$bank->kode} - {$bank->nama}";
        $bank->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Bank',
            "Menghapus {$label}.",
            'warning',
        );

        return redirect()
            ->route('bank.index')
            ->with('success', "Bank ({$label}) berhasil dihapus.");
    }
}
