<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index()
    {
        // PERBAIKAN: Admin bisa melihat semua laporan, User biasa hanya miliknya
        if (Auth::user()->role === 'admin') {
            $reports = Report::with('user')->latest()->get();
        } else {
            $reports = Report::where('user_id', Auth::id())->latest()->get();
        }

        return view('reports.index', compact('reports'));
    }

    public function create()
    {
        return view('reports.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255', // Sesuaikan dengan database (judul/title)
            'deskripsi' => 'required|string',
            'lokasi' => 'required|string',
            'foto' => 'nullable|image|max:2048', // Sesuaikan dengan database (foto/image)
        ]);

        $imagePath = null;
        if ($request->hasFile('foto')) {
            $imagePath = $request->file('foto')->store('reports', 'public');
        }

        // Pastikan nama kolom sesuai database migration Anda
        // Di migration sebelumnya Anda pakai: judul, deskripsi, lokasi, foto
        // Di controller lama Anda pakai: title, description, location, image
        // SAYA SESUAIKAN DENGAN MIGRATION (BAHASA INDONESIA)
        Report::create([
            'user_id' => Auth::id(),
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'lokasi' => $request->lokasi,
            'foto' => $imagePath,
            'status' => 'pending',
        ]);

        // Redirect ke Index reports, bukan Dashboard, agar user tahu laporannya masuk
        return redirect()->route('reports.index')->with('success', 'Laporan berhasil dibuat!');
    }

    public function updateStatus(Request $request, Report $report)
    {
        // Hanya Admin yang boleh validasi
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,validated,rejected,completed'
        ]);

        $report->update(['status' => $request->status]);

        return back()->with('success', 'Status laporan diperbarui!');
    }

    public function destroy($id)
    {
        $report = Report::findOrFail($id);

        if (Auth::id() !== $report->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        if ($report->foto) { // Gunakan 'foto' sesuai migration
            Storage::disk('public')->delete($report->foto);
        }

        $report->delete();

        return back()->with('success', 'Laporan berhasil dihapus.');
    }
}
