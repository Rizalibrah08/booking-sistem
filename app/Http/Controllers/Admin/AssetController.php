<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAssetRequest;
use App\Http\Requests\UpdateAssetRequest;
use App\Models\Asset;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AssetController extends Controller
{
    /**
     * Daftar semua aset.
     */
    public function index(): View
    {
        $assets = Asset::withCount('peminjamans')
            ->latest()
            ->paginate(15);

        return view('admin.assets.index', compact('assets'));
    }

    /**
     * Form tambah aset baru.
     */
    public function create(): View
    {
        return view('admin.assets.create');
    }

    /**
     * Simpan aset baru.
     */
    public function store(StoreAssetRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_restricted_for_student'] = $request->boolean('is_restricted_for_student');

        Asset::create($data);

        return redirect()
            ->route('admin.assets.index')
            ->with('success', 'Aset berhasil ditambahkan.');
    }

    /**
     * Form edit aset.
     */
    public function edit(Asset $asset): View
    {
        return view('admin.assets.edit', compact('asset'));
    }

    /**
     * Update aset.
     */
    public function update(UpdateAssetRequest $request, Asset $asset): RedirectResponse
    {
        $data = $request->validated();
        $data['is_restricted_for_student'] = $request->boolean('is_restricted_for_student');

        $asset->update($data);

        return redirect()
            ->route('admin.assets.index')
            ->with('success', 'Aset berhasil diperbarui.');
    }

    /**
     * Hapus aset.
     */
    public function destroy(Asset $asset): RedirectResponse
    {
        $asset->delete();

        return redirect()
            ->route('admin.assets.index')
            ->with('success', 'Aset berhasil dihapus.');
    }
}
