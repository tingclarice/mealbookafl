<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\MealOptionGroup;
use App\Models\MealOptionValue;
use Illuminate\Http\Request;

class MealOptionController extends Controller
{
    // Store new option group
    public function storeGroup(Request $request, Meal $meal)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'is_multiple' => 'required|boolean',
            'is_required' => 'required|boolean',
        ]);

        $validated['meal_id'] = $meal->id;
        $validated['is_multiple'] = $request->has('is_multiple');
        $validated['is_required'] = $request->has('is_required');

        MealOptionGroup::create($validated);

        return back()->with('success', 'Meal option group created successfully.');
    }

    // Update option group


    // Delete option group


    // Update option value


    // Delete option value

}
