<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use App\Http\Requests\ShopUpdateRequest;
use App\Http\Requests\ShopOwnerRequest;
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

        $whatsappMessage = "Selamat! Pendaftaran toko Anda di MealBook telah disetujui.\n\nToko Anda sekarang telah aktif dan siap menerima pesanan. Anda dapat segera login ke dashboard untuk mulai mengelola menu dan pesanan.\n\nSelamat berjualan dan terima kasih telah bergabung dengan MealBook!";
        GowaController::sendMessage($whatsappMessage, $shop->owner()->phone);

        return back()->with('success', 'Shop accepted successfully');
    }

    public function decline(Shop $shop, $message)
    {
        $whatsappMessage = "Terima kasih telah mendaftarkan toko Anda di MealBook. Kami menghargai minat Anda untuk bergabung dengan platform kami. Setelah melakukan peninjauan, pendaftaran toko Anda belum dapat kami setujui untuk saat ini karena alasan sebagai berikut :\n\n" . $message . "\n\nJangan khawatir! Anda dapat melakukan perbaikan pada data toko dan mengajukan ulang pendaftaran kapan saja.";
        
        GowaController::sendMessage($whatsappMessage, $shop->owner()->phone);
        // GowaController::sendMessage($whatsappMessage, $shop->phone);

        $shop->update(['status' => 'REJECTED']);
        return back()->with('success', 'Shop declined successfully');
    }

    public function suspend(Shop $shop, $message)
    {
        // Professional template for Suspension
        $whatsappMessage = "Pemberitahuan dari MealBook. Kami menginformasikan bahwa untuk sementara waktu, akun toko Anda telah ditangguhkan (SUSPENDED) karena alasan sebagai berikut:\n\n" . 
                        "\"" . $message . "\"\n\n" .
                        "Selama masa penangguhan, toko Anda tidak akan dapat menerima pesanan atau muncul di halaman pencarian pelanggan. Silakan hubungi tim dukungan kami atau perbaiki kendala terkait untuk proses pengaktifan kembali.";

        // Send via WhatsApp
        GowaController::sendMessage($whatsappMessage, $shop->owner()->phone);

        // Update status in database
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

    // Show Shop Details and their Menus
    public function show(Shop $shop)
    {
        // Only show approved shops to public
        if (!in_array($shop->status, ['OPEN', 'CLOSE'])) {
            abort(404, 'Shop not found');
        }

        $shop->load([
            'meals' => function ($query) {
                $query->where('isAvailable', true)->latest();
            }
        ]);

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

    public function update(ShopUpdateRequest $request)
    {

        $user = $request->user();
        $shop = $user->shops()->wherePivot('role', 'OWNER')->first();

        // Safety check if authorize() passed but somehow shop retrieval fails
        if (!$shop) {
             abort(404, 'Shop not found.');
        }

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

        return Redirect::route('profile.edit')->with('success', 'Shop information updated successfully');
    }
    public function addStaff(ShopOwnerRequest $request, Shop $shop)
    {        
        // Authorization handled by ShopOwnerRequest
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if ($user && ($user->isOwner() || $user->isStaff())) {
            return back()->with('error', 'This user is already an Owner or Staff of a shop.');
        }

        try {
            $shop->addStaff($request->email);
            return back()->with('success', 'Staff added successfully!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function removeStaff(ShopOwnerRequest $request, Shop $shop, User $user)
    {
        // Authorization handled by ShopOwnerRequest

        // Remove from UserRole
        UserRole::where('shop_id', $shop->id)
            ->where('user_id', $user->id)
            ->where('role', 'STAFF')
            ->delete();

        return response()->json(['success' => true]);
    }

    public function updateStaffNotification(ShopOwnerRequest $request, Shop $shop, User $user)
    {
        // Authorization handled by ShopOwnerRequest

        if (empty($user->phone)) {
            return response()->json(['success' => false, 'message' => 'Phone number must be filled.']);
        }

        $user->staff_notification = !$user->staff_notification;
        $user->save();

        return response()->json(['success' => true, 'status' => $user->staff_notification]);
    }

    public function destroy(ShopOwnerRequest $request, Shop $shop)
    {
        // Authorization handled by ShopOwnerRequest

        $hasActiveOrders = \App\Models\Order::where('shop_id', $shop->id)
            ->whereIn('order_status', ['PENDING', 'CONFIRMED', 'READY'])
            ->exists();

        if ($hasActiveOrders) {
            return back()->with('error', 'Cannot delete shop. There are active orders (Pending, Confirmed, or Ready). Please complete or cancel them first.');
        }

        // Deletion
        DB::beginTransaction();
        try {
            // Cleanup Images
            if ($shop->profileImage) {
                Storage::disk('public')->delete($shop->profileImage);
            }
            // Get all meals to delete their images
            $meals = $shop->meals;
            foreach ($meals as $meal) {
                foreach($meal->images as $image) {
                     Storage::disk('public')->delete($image->image_path);
                }
            }

            // Detach all users (Owner & Staff)
            $shop->users()->detach();

            // delete meals then shop
            $shop->meals()->delete();
            $shop->delete();

            DB::commit();

            return redirect()->route('profile.edit')->with('success', 'Shop deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete shop: ' . $e->getMessage());
        }
    }
}
