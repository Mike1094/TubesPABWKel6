<?php

namespace App\Http\Controllers;

use App\Models\Gate;
use App\Models\LostFoundItem;
use App\Models\Report;
use App\Models\TrafficUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            'reports_total'   => Report::count(),
            'lost_items'      => LostFoundItem::where('type', 'lost')->where('status', 'open')->count(),
            'found_items'     => LostFoundItem::where('type', 'found')->where('status', 'open')->count(),
            'open_gates'      => Gate::where('status', 'open')->count(),
        ];

        $pendingReports = Report::where('status', 'pending')->with('user')->latest()->take(5)->get();
        $pendingLostFound = LostFoundItem::where('status', 'pending')->with('user')->latest()->take(5)->get();

        $chartData = Report::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->pluck('total', 'date');

        return view('dashboard.admin', compact('stats', 'pendingReports', 'pendingLostFound', 'chartData'));
    }

    private function satpamDashboard()
    {
        $gates = Gate::all();
        $trafficUpdates = TrafficUpdate::latest()->take(5)->get();

        return view('dashboard.satpam', compact('gates', 'trafficUpdates'));
    }

    private function civitasDashboard()
    {
        $myReports = Report::where('user_id', Auth::id())->latest()->take(5)->get();
        $myLostFound = LostFoundItem::where('user_id', Auth::id())->latest()->take(5)->get();

        $cctvs = $this->getMockCctvs();
        $gates = Gate::all();

        return view('dashboard.civitas', compact('myReports', 'myLostFound', 'cctvs', 'gates'));
    }

    private function wargaDashboard()
    {
        $cctvs = $this->getMockCctvs();
        $trafficUpdates = TrafficUpdate::latest()->take(5)->get();
        $gates = Gate::all();

        return view('dashboard.warga', compact('cctvs', 'trafficUpdates', 'gates'));
    }

    private function getMockCctvs()
    {
        return [
            [
                'name' => 'Gerbang Depan',
                'status' => 'Online',
                'image' => 'https://images.unsplash.com/photo-1565514020176-1c25039df8eb?auto=format&fit=crop&w=400&q=80'
            ],
            [
                'name' => 'Parkiran Gd. A',
                'status' => 'Online',
                'image' => 'https://images.unsplash.com/photo-1590674899505-1c5c4127193c?auto=format&fit=crop&w=400&q=80'
            ],
            [
                'name' => 'Kantin Asrama',
                'status' => 'Maintenance',
                'image' => 'https://images.unsplash.com/photo-1555447425-69bc336b7325?auto=format&fit=crop&w=400&q=80'
            ],
            [
                'name' => 'Perpustakaan',
                'status' => 'Online',
                'image' => 'https://images.unsplash.com/photo-1568667256549-094345857637?auto=format&fit=crop&w=400&q=80'
            ],
        ];
    }
}
