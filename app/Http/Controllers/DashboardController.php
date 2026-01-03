<?php

namespace App\Http\Controllers;

use App\Models\Gate;
use App\Models\LostFoundItem;
use App\Models\Report;
use App\Models\TrafficUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role;

        switch ($role) {
            case 'admin':
                return $this->adminDashboard();
            case 'satpam':
                return $this->satpamDashboard();
            case 'warga':
                return $this->wargaDashboard();
            default:
                return $this->civitasDashboard();
        }
    }

    private function adminDashboard()
    {
        $stats = [
            'reports_pending' => Report::where('status', 'pending')->count(),
            'reports_total' => Report::count(),
            'lost_items' => LostFoundItem::where('type', 'lost')->where('status', 'open')->count(),
            'found_items' => LostFoundItem::where('type', 'found')->where('status', 'open')->count(),
        ];
        
        $pendingReports = Report::where('status', 'pending')->with('user')->latest()->get();
        $pendingLostFound = LostFoundItem::where('status', 'pending')->with('user')->latest()->get();
        
        return view('dashboard.admin', compact('stats', 'pendingReports', 'pendingLostFound'));
    }

    private function satpamDashboard()
    {
        $gates = Gate::all();
        $trafficUpdates = TrafficUpdate::latest()->take(3)->get();
        return view('dashboard.satpam', compact('gates', 'trafficUpdates'));
    }

    private function civitasDashboard()
    {
        $myReports = Report::where('user_id', Auth::id())->latest()->get();
        $myLostFound = LostFoundItem::where('user_id', Auth::id())->latest()->get();
        
        return view('dashboard.civitas', compact('myReports', 'myLostFound'));
    }

    private function wargaDashboard()
    {
        // Mock CCTV Data
        $cctvs = [
            ['name' => 'Gerbang Depan', 'status' => 'Online', 'image' => 'https://images.unsplash.com/photo-1565514020176-1c25039df8eb?auto=format&fit=crop&w=400&q=80'],
            ['name' => 'Parkiran Gd. A', 'status' => 'Online', 'image' => 'https://images.unsplash.com/photo-1590674899505-1c5c4127193c?auto=format&fit=crop&w=400&q=80'],
            ['name' => 'Kantin Asrama', 'status' => 'Maintenance', 'image' => 'https://images.unsplash.com/photo-1555447425-69bc336b7325?auto=format&fit=crop&w=400&q=80'],
            ['name' => 'Perpustakaan', 'status' => 'Online', 'image' => 'https://images.unsplash.com/photo-1568667256549-094345857637?auto=format&fit=crop&w=400&q=80'],
        ];
        
        $trafficUpdates = TrafficUpdate::latest()->take(3)->get();
        
        return view('dashboard.warga', compact('cctvs', 'trafficUpdates'));
    }
}
