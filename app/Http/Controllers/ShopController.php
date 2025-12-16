<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{
    public function shopApprovals()
    {
        $pendingShops = Shop::where('status', 'PENDING')->get();
        $activeShops = Shop::whereIn('status', ['OPEN', 'CLOSE'])->get();
        $rejectedShops = Shop::where('status', "REJECTED")->get();
        $suspendedShops = Shop::where('status', "SUSPENDED")->get();
        return view('dashboard.shopApprovals', compact('pendingShops', 'activeShops', 'rejectedShops', 'suspendedShops'));
    }

    public function accept(Shop $shop)
    {
        $shop->update(['status' => 'OPEN']);
        return back()->with('success', 'Shop accepted successfully');
    }

    public function decline(Shop $shop, $message)
    {
        GowaController::sendMessage($message, $shop->users()->first()->id);

        $shop->update(['status' => 'REJECTED']);
        return back()->with('success', 'Shop declined successfully');
    }

    public function suspend(Shop $shop, $message)
    {
        GowaController::sendMessage($message, $shop->users()->first()->id);

        $shop->update(['status' => 'SUSPENDED']);
        return back()->with('success', 'Shop suspended successfully');
    }

    public function request(Request $request)
    {
        // Check if user already has a shop
        if (UserRole::where('user_id', $request->user()->id)->exists()) {
            return back()->with('error', 'You have already registered a shop.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string',
            'description' => 'required|string',
            'profileImage' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $path = $request->file('profileImage')->store('shops', 'public');

            $shop = Shop::create([
                'name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone,
                'description' => $request->description,
                'profileImage' => $path,
                'status' => 'PENDING',
                'user_id' => $request->user()->id
            ]);

            UserRole::create([
                'user_id' => $request->user()->id,
                'shop_id' => $shop->id,
                'role' => 'OWNER'
            ]);

            DB::commit();

            return back()->with('success', 'Shop request submitted!');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'profileImage' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // 2MB Max
            // ... other validations
        ]);

        $user = $request->user();
        $shop = $user->shops()->wherePivot('role', 'OWNER')->first();

        $data = $request->except('profileImage');

        // Handle Image Upload
        if ($request->hasFile('profileImage')) {
            // Delete old image if exists
            if ($shop->profileImage) {
                Storage::disk('public')->delete($shop->profileImage);
            }
            // Store new image
            $path = $request->file('profileImage')->store('shops', 'public');
            $data['profileImage'] = $path;
        }

        $shop->update($data);

        return Redirect::route('profile.edit')->with('status', 'shop-updated');
    }
}
