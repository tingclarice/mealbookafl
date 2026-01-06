<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FilterButton extends Component
{
    public $label;
    public $category;
    public $isActive;

    // Create a new component instance
    public function __construct($label, $category = null, $isActive = false)
    {
        $this->label = $label;
        $this->category = $category;
        $this->isActive = $isActive;
    }

    // Get the view / contents that represent the component
    public function render(): View|Closure|string
    {
        return view('components.filter-button');
    }
}
