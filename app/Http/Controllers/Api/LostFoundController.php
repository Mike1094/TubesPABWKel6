<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LostFoundItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LostFoundController extends Controller
{
    public function index(Request $request)
    {
        $query = LostFoundItem::with('user');

        if ($request->has('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return response()->json($query->latest()->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'lokasi_ditemukan' => 'nullable|string',
            'jenis' => 'required|in:hilang,ditemukan',
            'foto' => 'nullable|image|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('lost-found', 'public');
        }

        $item = LostFoundItem::create([
            'user_id' => $request->user()->id,
            'nama_barang' => $validated['nama_barang'],
            'deskripsi' => $validated['deskripsi'],
            'lokasi_ditemukan' => $validated['lokasi_ditemukan'],
            'jenis' => $validated['jenis'],
            'status' => 'open',
            'foto' => $path,
        ]);

        return response()->json([
            'message' => 'Laporan barang berhasil dibuat',
            'data' => $item
        ], 201);
    }

    public function show($id)
    {
        $item = LostFoundItem::with('user')->find($id);

        if (!$item) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $item = LostFoundItem::find($id);

        if (!$item) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $this->authorize('update', $item);

        $validated = $request->validate([
            'nama_barang' => 'sometimes|string|max:255',
            'deskripsi' => 'sometimes|string',
            'lokasi_ditemukan' => 'nullable|string',
            'status' => 'sometimes|in:open,claimed,resolved',
        ]);

        $item->update($validated);

        return response()->json([
            'message' => 'Data berhasil diperbarui',
            'data' => $item
        ]);
    }

    public function destroy($id)
    {
        $item = LostFoundItem::find($id);

        if (!$item) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $this->authorize('delete', $item);

        if ($item->foto) {
            Storage::disk('public')->delete($item->foto);
        }

        $item->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
