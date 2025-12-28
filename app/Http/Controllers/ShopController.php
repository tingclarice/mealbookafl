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
        // GowaController::sendMessage($message, $shop->users()->first()->id);
        GowaController::sendMessage($message, $shop->phone);

        $shop->update(['status' => 'REJECTED']);
        return back()->with('success', 'Shop declined successfully');
    }

    public function suspend(Shop $shop, $message)
    {
        // GowaController::sendMessage($message, $shop->users()->first()->id);
        GowaController::sendMessage($message, $shop->phone);

        $shop->update(['status' => 'SUSPENDED']);
        return back()->with('success', 'Shop suspended successfully');
    }

    public function request(Request $request)
    {
        // dd(
        //     $request->all(),
        //     $request->file('profileImage')->getMimeType(),
        //     $request->file('profileImage')->getClientOriginalExtension(),
        //     $request->file('profileImage')->getSize(),
        //     file_get_contents($request->file('profileImage')->getPathname(), false, null, 0, 20)
        // );

        // Check if user already has a shop
        if (UserRole::where('user_id', $request->user()->id)->exists()) {
            return back()->with('error', 'You have already registered a shop.');
        }

        $request->validate([
            // 1. The Rules
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string',
            'description' => 'required|string',
            // 'profileImage' => 'required|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            // Name Messages
            'name.required' => 'Please enter the shop name.',
            'name.max' => 'The shop name cannot exceed 255 characters.',
            
            // Address Messages
            'address.required' => 'The shop address is required.',
            
            // Phone Messages
            'phone.required' => 'Please provide a contact phone number.',
            
            // Description Messages
            'description.required' => 'A description of your shop is required.',
            
            // Profile Image Messages
            'profileImage.required' => 'You must upload a profile image for the shop.',
            'profileImage.image' => 'The uploaded file must be an image.',
            'profileImage.mimes' => 'The image must be a file of type: jpg, jpeg, png, or webp.',
            'profileImage.max' => 'Maximum image size is 2 MB.',
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

    public function show(Shop $shop){
        // Only show approved shops to public
        if (!in_array($shop->status, ['OPEN', 'CLOSE'])) {
            abort(404, 'Shop not found');
        }
        
        $shop->load(['meals' => function($query) {
            $query->where('isAvailable', true)->latest();
        }]);
        
        return view('shop.show', compact('shop'));
    }
    public function cancelRequest(Request $request)
    {
        $user = $request->user();
        
        // Find the shop where the user is an owner, even if pending/rejected
        // We use the relationship defined in User model (assuming one exists) or query UserRole
        $userRole = UserRole::where('user_id', $user->id)->where('role', 'OWNER')->first();

        if (!$userRole) {
            return back()->with('error', 'No shop request found to cancel.');
        }

        $shop = Shop::find($userRole->shop_id);

        if (!$shop) {
             // Clean up orphan role if shop is gone
            $userRole->delete();
            return back()->with('error', 'Shop not found.');
        }

        DB::beginTransaction();

        try {
            // Delete Image
            if ($shop->profileImage) {
                Storage::disk('public')->delete($shop->profileImage);
            }

            // Delete Role
            $userRole->delete();

            // Delete Shop
            $shop->delete();

            DB::commit();

            return back()->with('success', 'Shop request cancelled successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to cancel request: ' . $e->getMessage());
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
