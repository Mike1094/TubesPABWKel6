<?php

namespace App\Http\Controllers;

use App\Models\LostFoundItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Notifications\ItemFound;
use Illuminate\Support\Facades\Notification;

class LostFoundController extends Controller
{
    public function index()
    {
        $items = LostFoundItem::whereIn('status', ['open', 'pending', 'claimed'])->latest()->get();
        return view('lost-found.index', compact('items'));
    }

    public function create(Request $request)
    {
        // Get list of lost items to populate the dropdown for "Found" reports
        $lostItems = LostFoundItem::where('type', 'lost')->where('status', 'open')->get();
        return view('lost-found.create', [
            'lostItems' => $lostItems,
            'type' => $request->query('type'),
            'linked_lost_id' => $request->query('linked_lost_id')
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'type' => 'required|in:lost,found',
            'description' => 'required|string',
            'location' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'linked_lost_id' => 'nullable|exists:lost_found_items,id',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('lost-found-images', 'public');
        }

        $item = LostFoundItem::create([
            'user_id' => Auth::id(),
            'item_name' => $request->item_name,
            'type' => $request->type,
            'description' => $request->description,
            'location' => $request->location,
            'image' => $imagePath,
            'status' => 'pending', // Pending Admin Approval
            'linked_lost_id' => $request->linked_lost_id,
        ]);

        // If this is a "Found" item and it is linked to a "Lost" item, notify the owner
        if ($request->type === 'found' && $request->linked_lost_id) {
            $lostItem = LostFoundItem::find($request->linked_lost_id);
            if ($lostItem) {
                $lostItem->update(['status' => 'claimed']);
                if ($lostItem->user) {
                    $lostItem->user->notify(new ItemFound(Auth::user()->name, $request->item_name));
                }
            }
        }

        return redirect()->route('lost-found.index')->with('success', 'Barang berhasil dilaporkan!');
    }
    
    public function update(Request $request, LostFoundItem $lostFoundItem)
    {
        // Allow Admin or Owner to resolve
        if (Auth::user()->role !== 'admin' && Auth::id() !== $lostFoundItem->user_id) {
            abort(403);
        }

        $lostFoundItem->update(['status' => 'resolved']);
        return back()->with('success', 'Status barang diperbarui!');
    }

    public function updateStatus(Request $request, LostFoundItem $lostFoundItem)
    {
        $request->validate([
            'status' => 'required|in:open,resolved,claimed,validated'
        ]);

        $lostFoundItem->update(['status' => $request->status]);

        return back()->with('success', 'Status laporan barang diperbarui!');
    }
}
