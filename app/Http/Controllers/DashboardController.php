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
        // 1. Ambil data count untuk statistik
        $total_reports = Report::count();
        $pending_reports = Report::where('status', 'pending')->count();
        $open_gates = Gate::where('status', 'open')->count();

        // 2. Data tambahan untuk Admin (Lost & Found)
        $lost_items = LostFoundItem::where('jenis', 'hilang')->where('status', 'open')->count();
        $found_items = LostFoundItem::where('jenis', 'ditemukan')->where('status', 'open')->count();

        // 3. Data Tabel Validasi
        $recent_reports = Report::where('status', 'pending')->with('user')->latest()->take(5)->get();

        // 4. Data Grafik Analisa Harian
        $chart_data = Report::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->pluck('count', 'date');

        // RETURN: Menggunakan compact() dengan nama variabel yang sesuai di View
        return view('dashboard.admin', compact(
            'total_reports',
            'pending_reports',
            'open_gates',
            'recent_reports',
            'chart_data',
            'lost_items',
            'found_items'
        ));
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
            ['name' => 'Gerbang Depan', 'status' => 'Online', 'image' => 'https://images.unsplash.com/photo-1565514020176-1c25039df8eb?auto=format&fit=crop&w=400&q=80'],
            ['name' => 'Parkiran Gd. A', 'status' => 'Online', 'image' => 'https://images.unsplash.com/photo-1590674899505-1c5c4127193c?auto=format&fit=crop&w=400&q=80'],
            ['name' => 'Kantin Asrama', 'status' => 'Maintenance', 'image' => 'https://images.unsplash.com/photo-1555447425-69bc336b7325?auto=format&fit=crop&w=400&q=80'],
            ['name' => 'Perpustakaan', 'status' => 'Online', 'image' => 'https://images.unsplash.com/photo-1568667256549-094345857637?auto=format&fit=crop&w=400&q=80'],
        ];
    }
}
