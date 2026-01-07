<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // GET All Reports (Mendukung filter status untuk admin)
    public function index(Request $request)
    {
        $query = Report::with('user');
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        return response()->json($query->get());
    }

    // POST Create Report
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string',
            'deskripsi' => 'required|string',
            'lokasi' => 'required|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('reports', 'public');
        }

        $report = Report::create([
            'user_id' => $request->user()->id,
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'lokasi' => $validated['lokasi'],
            'foto' => $path,
        ]);

        return response()->json(['message' => 'Laporan berhasil dibuat', 'data' => $report], 201);
    }
}
