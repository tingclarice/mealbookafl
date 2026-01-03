<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\MealImage;
use App\Models\Review;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MealController extends Controller
{
    // Untuk halaman /menu (list + filter)
    public function index(Request $request)
    {
        $query = Meal::with('shop')
            ->where('isAvailable', true)
            ->whereHas('shop', function ($q) {
                $q->where('status', 'OPEN');
            });

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
    public function show($id)
    {
        $meal = Meal::with([
            'optionGroups.values',
            'shop',
            'images'
        ])
        ->whereHas('shop', function ($q) {
            $q->where('status', 'OPEN');
        })
        ->findOrFail($id);

        // $reviews = $meal->reviews;
        // $averageRating = round($reviews->avg('rate'), 1);
        // $reviewCount = $reviews->count();
        // $latestReviews = $reviews->sortByDesc('created_at')->take(2);

        $averageRating = "";
        $reviewCount = "";
        $latestReviews = [];

        $suggestedMeals = Meal::where('id', '!=', $meal->id)
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

    // Store new meal
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'category' => 'required|in:MEAL,SNACK,DRINK',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            ]);

            // Get shop ID from relationship
            $shop = $request->user()->shops()->first();

            if (!$shop) {
                return back()->withErrors(['error' => 'User is not assigned to any shop']);
            }

            $validated['shop_id'] = $shop->id;

            // Handle legacy single image upload (backward compatibility)
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('images/meals', 'public');
                $validated['image_url'] = $imagePath;
            }

            $validated['isAvailable'] = $request->has('isAvailable');

            $meal = Meal::create($validated);

            // Handle multiple images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $imagePath = $image->store('images/meals', 'public');

                    MealImage::create([
                        'meal_id' => $meal->id,
                        'image_path' => $imagePath,
                        'order' => $index,
                        'is_primary' => $index === 0, // First image is primary
                    ]);
                }

                // Sync legacy column with the new primary image
                $this->syncPrimaryImage($meal);
            }

            return redirect()->route('dashboard')->with('success', 'Menu berhasil ditambahkan!');
        } catch (Exception $e) {
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
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Handle legacy single image upload (backward compatibility)
        if ($request->hasFile('image')) {
            // Delete old image
            if ($meal->image_url) {
                Storage::disk('public')->delete($meal->image_url);
            }

            $imagePath = $request->file('image')->store('images/meals', 'public');
            $validated['image_url'] = $imagePath;
        }

        // Handle multiple new images
        if ($request->hasFile('images')) {
            $currentMaxOrder = $meal->images()->max('order') ?? -1;

            foreach ($request->file('images') as $index => $image) {
                $imagePath = $image->store('images/meals', 'public');

                // If this is the first image overall, make it primary
                $isPrimary = $meal->images()->count() === 0 && $index === 0;

                MealImage::create([
                    'meal_id' => $meal->id,
                    'image_path' => $imagePath,
                    'order' => $currentMaxOrder + $index + 1,
                    'is_primary' => $isPrimary,
                ]);
            }

            $this->syncPrimaryImage($meal);
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

        // Delete legacy single image if exists
        if ($meal->image_url) {
            Storage::disk('public')->delete($meal->image_url);
        }

        // Delete all meal images
        foreach ($meal->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $meal->delete();

        return redirect()->route('dashboard')->with('success', 'Menu berhasil dihapus!');
    }

    // Delete individual image
    public function deleteImage($mealId, $imageId)
    {
        $meal = Meal::findOrFail($mealId);
        $image = MealImage::where('meal_id', $mealId)->where('id', $imageId)->firstOrFail();

        $wasPrimary = $image->is_primary;

        // Delete the file
        Storage::disk('public')->delete($image->image_path);

        // Delete the record
        $image->delete();

        // If deleted image was primary, set another image as primary
        if ($wasPrimary) {
            $newPrimary = $meal->images()->first();
            if ($newPrimary) {
                $newPrimary->update(['is_primary' => true]);
            }
        }

        $this->syncPrimaryImage($meal);

        return response()->json(['success' => true, 'message' => 'Image deleted successfully']);
    }

    // Get meal images (AJAX)
    public function getMealImages($mealId)
    {
        $meal = Meal::with('images')->findOrFail($mealId);

        // Migrate legacy image to meal_images if it exists and no images are set yet
        if ($meal->hasLegacyImage()) {
            MealImage::create([
                'meal_id' => $meal->id,
                'image_path' => $meal->image_url,
                'order' => 0,
                'is_primary' => true
            ]);
            // Reload images after migration
            $meal = Meal::with('images')->findOrFail($mealId);
        }

        $images = $meal->images->map(function ($image) {
            return [
                'id' => $image->id,
                'path' => $image->image_path,
                'is_primary' => $image->is_primary,
                'order' => $image->order
            ];
        });

        return response()->json(['images' => $images]);
    }

    // Set primary image
    public function setPrimaryImage($mealId, $imageId)
    {
        $meal = Meal::findOrFail($mealId);

        // Remove primary flag from all images
        $meal->images()->update(['is_primary' => false]);

        // Set new primary image
        $image = MealImage::where('meal_id', $mealId)->where('id', $imageId)->firstOrFail();
        $image->update(['is_primary' => true]);

        // Sync with legacy column
        $this->syncPrimaryImage($meal);

        return response()->json(['success' => true, 'message' => 'Primary image updated']);
    }

    private function syncPrimaryImage(Meal $meal)
    {
        $primaryImage = $meal->images()->where('is_primary', true)->first();

        if ($primaryImage) {
            // Update the legacy image_url column to match the primary image
            $meal->update(['image_url' => $primaryImage->image_path]);
        } else {
            // If no primary image found but images exist, mark the first one as primary
            $firstImage = $meal->images()->orderBy('order')->first();
            if ($firstImage) {
                $firstImage->update(['is_primary' => true]);
                $meal->update(['image_url' => $firstImage->image_path]);
            }
        }
    }
}
