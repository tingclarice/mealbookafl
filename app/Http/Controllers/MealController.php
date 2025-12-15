<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\Review;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class MealController extends Controller
{
    // Untuk halaman /menu (list + filter)
    public function index(Request $request)
    {
        $query = Meal::where('isAvailable', true);
        
        $currentCategory = $request->get('category');

        $searchQuery = $request->get('search');
        
        if ($currentCategory) {
            $query->where('category', $currentCategory);
        }

        if ($searchQuery) {
            $query->where('name', 'like', '%' . $searchQuery . '%');
        }
        
        $meals = $query->paginate(12);
        
        $categories = [
            'MEAL' => 'Makanan',
            'SNACK' => 'Snack',
            'DRINK' => 'Minuman'
        ];
        
        // return view('menu.index', compact('meals', 'categories', 'currentCategory'));
        return view('menu.index', compact('meals', 'categories', 'currentCategory', 'searchQuery'));
    }

    // Untuk halaman /menu/{id} (detail)
    public function show($id){
        $meal = Meal::with([
            'optionGroups.values',
            'shop', // Load shop relationship
            'images' // Load images relationship
        ])->findOrFail($id);

        // $reviews = $meal->reviews;
        // $averageRating = round($reviews->avg('rate'), 1);
        // $reviewCount = $reviews->count();
        // $latestReviews = $reviews->sortByDesc('created_at')->take(2);
        
        $averageRating = "";
        $reviewCount = "";
        $latestReviews = [];
        
        $suggestedMeals = Meal::where('id', '!=', $meal->id)
                            ->where('shop_id', $meal->shop_id) // Same shop
                            ->inRandomOrder()
                            ->take(4)
                            ->get();

        return view('menu.show', compact('meal', 'averageRating', 'reviewCount', 'latestReviews', 'suggestedMeals'));
    }

    // public function reviews($id){
    //     $meal = Meal::with(['reviews.user'])->findOrFail($id);
    //     $reviews = $meal->reviews->sortByDesc('created_at');
    //     $averageRating = round($reviews->avg('rate'), 1);
    //     $reviewCount = $reviews->count();
    //     return view('menu.reviews', compact('meal', 'reviews', 'averageRating', 'reviewCount'));
    // }

    // Store new meal (w/ multiple images, max 5)
    public function store(Request $request){
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'category' => 'required|in:MEAL,SNACK,DRINK',
                'images' => 'nullable|array|max:5',
                'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            ]);

            // Get shop ID from relationship
            $shop = $request->user()->shops()->first();

            if (!$shop) {
                return back()->withErrors(['error' => 'User is not assigned to any shop']);
            }

            DB::beginTransaction();

            $validated['shop_id'] = $shop->id;
            $validated['isAvailable'] = $request->has('isAvailable');

            // Create meal
            $meal = Meal::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'category' => $validated['category'],
                'shop_id' => $validated['shop_id'],
                'isAvailable' => $validated['isAvailable']
            ]);

            // Handle multiple image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('images/meals', 'public');
                $validated['image_url'] = $imagePath;
            }

            $validated['isAvailable'] = $request->has('isAvailable');

            Meal::create($validated);

            return redirect()->route('dashboard')->with('success', 'Menu berhasil ditambahkan!');
        } 
        catch (Exception $e) {
            Log::error('Failed to store meal: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data.'])->withInput();
        }
    }

    // Update meal
    public function update(Request $request, $id)
    {
        $meal = Meal::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|in:MEAL,SNACK,DRINK',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($meal->image_url) {
                Storage::disk('public')->delete($meal->image_url);
            }
            
            $imagePath = $request->file('image')->store('menu_images', 'public');
            $validated['image_url'] = $imagePath;
        }

        $shop = $request->user()->shops()->first();
        $validated['shop_id'] = $shop->id;
        $validated['isAvailable'] = $request->has('isAvailable') ? true : false;

        $meal->update($validated);

        return redirect()->route('dashboard')->with('success', 'Menu berhasil diperbarui!');
    }

    // Delete meal
    public function destroy($id)
    {
        $meal = Meal::findOrFail($id);
        
        // Delete image if exists
        if ($meal->image_url) {
            Storage::disk('public')->delete($meal->image_url);
        }
        
        $meal->delete();

        return redirect()->route('dashboard')->with('success', 'Menu berhasil dihapus!');
    }
}

