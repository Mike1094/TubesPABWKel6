<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reports = Report::where('user_id', Auth::id())->latest()->get();
        return view('reports.index', compact('reports'));
    }

    /**
     * Show report form.
     */
    public function create()
    {
        return view('reports.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('reports', 'public');
        }

        Report::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'image' => $imagePath,
            'status' => 'pending',
        ]);

        return redirect()->route('dashboard')->with('success', 'Laporan berhasil dibuat!');
    }

    public function updateStatus(Request $request, Report $report)
    {
        $request->validate([
            'status' => 'required|in:pending,validated,in_progress,completed,rejected'
        ]);

        $report->update(['status' => $request->status]);

        return back()->with('success', 'Status laporan diperbarui!');
    }

    public function destroy($id)
{
    // Cari data secara manual berdasarkan ID
    $report = Report::findOrFail($id);

    // Cek otorisasi
    if (Auth::id() !== $report->user_id && Auth::user()->role !== 'admin') {
        abort(403, 'Unauthorized action.');
    }

    // Hapus gambar jika ada
    if ($report->image) {
        Storage::disk('public')->delete($report->image);
    }

    // Hapus data
    $report->delete();

    return back()->with('success', 'Laporan berhasil dihapus.');
}
}
