<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProjectController extends Controller
{
    // Display all projects
    public function index()
    {
        return view('products.index', [
            'title' => 'All Products',
            'projects' => Product::all()
        ]);
    }
}
