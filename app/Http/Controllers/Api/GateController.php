<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gate;
use App\Models\TrafficUpdate;
use Illuminate\Http\Request;

class GateController extends Controller
{
    public function index()
    {
        $gates = Gate::all();
        return response()->json($gates);
    }

    public function show($id)
    {
        $gate = Gate::find($id);
        if (!$gate) {
            return response()->json(['message' => 'Gate tidak ditemukan'], 404);
        }
        return response()->json($gate);
    }

    public function update(Request $request, $id)
    {
        $gate = Gate::find($id);

        if (!$gate) {
            return response()->json(['message' => 'Gate tidak ditemukan'], 404);
        }

        $user = $request->user();
        if (!in_array($user->role, ['satpam', 'admin'])) {
            return response()->json(['message' => 'Unauthorized. Hanya Satpam/Admin.'], 403);
        }

        $validated = $request->validate([
            'status' => 'sometimes|in:open,closed',
            'traffic_status' => 'sometimes|in:lancar,padat,macet',
        ]);

        $gate->update($validated);

        if ($request->has('traffic_status')) {
            TrafficUpdate::create([
                'gate_id' => $gate->id,
                'user_id' => $user->id,
                'status' => $request->traffic_status,
                'description' => 'Update status via API'
            ]);
        }

        return response()->json([
            'message' => 'Status Gate berhasil diperbarui',
            'data' => $gate
        ]);
    }
}
