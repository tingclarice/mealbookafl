<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\MealOptionGroup;
use App\Models\MealOptionValue;
use Illuminate\Http\Request;

class MealOptionController extends Controller
{
    // ========================================
    // OPTION GROUP METHODS
    // ========================================

    // Store new option group
    public function storeGroup(Request $request, Meal $meal)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'is_multiple' => 'nullable|boolean',
            'is_required' => 'nullable|boolean',
        ]);

        $validated['meal_id'] = $meal->id;
        $validated['is_multiple'] = $request->has('is_multiple');
        $validated['is_required'] = $request->has('is_required');

        MealOptionGroup::create($validated);

        return back()->with('success', 'Meal option group created successfully.');
    }

    // Update option group
    public function updateGroup(Request $request, MealOptionGroup $group)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'is_multiple' => 'boolean',
            'is_required' => 'boolean',
        ]);

        $group->update([
            'name' => $validated['name'],
            'is_multiple' => $request->has('is_multiple'),
            'is_required' => $request->has('is_required'),
        ]);

        return back()->with('success', 'Option group updated successfully!');
    }

    // Delete option group
    public function destroyGroup(MealOptionGroup $group)
    {
        $groupName = $group->name;
        $group->delete(); // Cascade will delete all values too

        return back()->with('success', "Option group '{$groupName}' deleted successfully!");
    }

    // ========================================
    // OPTION VALUE METHODS
    // ========================================
    // Store new option value
    public function storeValue(Request $request, MealOptionGroup $group)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
        ]);

        $group->values()->create([
            'name' => $validated['name'],
            'price' => $validated['price'],
        ]);

        return back()->with('success', 'Option value added successfully!');
    }

    // Update option value
    public function updateValue(Request $request, MealOptionValue $value)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
        ]);

        $value->update([
            'name' => $validated['name'],
            'price' => $validated['price'],
        ]);

        return back()->with('success', 'Option value updated successfully!');
    }

    // Delete option value
    public function destroyValue(MealOptionValue $value)
    {
        $valueName = $value->name;
        $value->delete();

        return back()->with('success', "Option value '{$valueName}' deleted successfully!");
    }

    // ========================================
    // HELPER METHOD
    // ========================================
    
    // Get all options for a specific meal (for AJAX)
    public function getMealOptions(Meal $meal)
    {
        $meal->load(['optionGroups.values']);
        
        return response()->json([
            'meal' => $meal,
            'optionGroups' => $meal->optionGroups
        ]);
    }
}
