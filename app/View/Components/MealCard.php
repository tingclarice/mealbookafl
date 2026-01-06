<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Meal;

class MealCard extends Component
{
    public $meal;

    // Create a new component instance
    public function __construct(Meal $meal)
    {
        $this->meal = $meal;
    }

    // Get the view / contents that represent the component
    public function render(): View|Closure|string
    {
        return view('components.meal-card');
    }
}
