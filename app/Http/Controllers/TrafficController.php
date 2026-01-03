<?php

namespace App\Http\Controllers;

use App\Models\Gate;
use App\Models\TrafficUpdate;
use App\Models\User;
use App\Notifications\TrafficInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class TrafficController extends Controller
{
    public function index()
    {
        $gates = Gate::all();
        $trafficUpdates = TrafficUpdate::with('user')->latest()->take(5)->get();
        return view('traffic.index', compact('gates', 'trafficUpdates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'location' => 'required|string',
            'status' => 'required|in:lancar,padat,macet',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('traffic-images', 'public');
        }

        $trafficUpdate = TrafficUpdate::create([
            'user_id' => Auth::id(),
            'location' => $request->location,
            'status' => $request->status,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        // Notify all Civitas
        $civitasUsers = User::where('role', 'civitas')->get();
        Notification::send($civitasUsers, new TrafficInfo($trafficUpdate));

        return back()->with('success', 'Update lalu lintas berhasil & notifikasi dikirim ke Civitas!');
    }

    public function updateGate(Request $request, Gate $gate)
    {
        $request->validate([
            'status' => 'required|in:lancar,padat,macet,tutup',
        ]);

        $gate->update([
            'status' => $request->status,
            'last_updated_by' => Auth::id(),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Status gerbang diperbarui!',
                'new_status' => $gate->status,
            ]);
        }

        return back()->with('success', 'Status gerbang diperbarui!');
    }
}
