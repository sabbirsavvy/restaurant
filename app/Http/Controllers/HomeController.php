<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuItem;
use App\Models\OpeningHour;


class HomeController extends Controller
{
    public function index()
    {
        // Fetch only the featured dishes
        $featuredDishes = MenuItem::where('is_featured', true)->get();
        return view('home', compact('featuredDishes'));
    }
}
