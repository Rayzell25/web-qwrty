<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Faq;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::active()
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        $featuredProducts = Product::active()
            ->featured()
            ->with('category')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->take(8)
            ->get();

        $latestProducts = Product::active()
            ->with('category')
            ->orderByDesc('id')
            ->take(8)
            ->get();

        $faqs = Faq::active()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->take(5)
            ->get();

        return view('home', compact('banners', 'featuredProducts', 'latestProducts', 'faqs'));
    }
}
