<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomepageController extends Controller
{
    public function index()
    {
        // Data untuk navigasi header
        $nav_links = [
            'TOPI', 'KAOS', 'SABLON', 'JAKET', 'JERSEY', 'TAS'
        ];

        // Data untuk kategori produk
        $categories = [
            ['name' => 'Topi',   'icon' => 'fa-solid fa-hat-cowboy'],
            ['name' => 'Kaos',   'icon' => 'fa-solid fa-shirt'],
            ['name' => 'Polo',   'icon' => 'fa-solid fa-spray-can-sparkles'],
            ['name' => 'Jaket',  'icon' => 'fa-solid fa-vest'],
            ['name' => 'Jersey', 'icon' => 'fa-solid fa-futbol'],
            ['name' => 'Tas',    'icon' => 'fa-solid fa-bag-shopping'],
        ];

        // Data untuk produk populer
        $products = [
            [
                'id'    => 1,
                'name'  => 'Jersey Bola Indonesia',
                'price' => 900000,
                'image' => 'assets/jersey-indonesia.jpg',
                'sizes' => null
            ],
            [
                'id'    => 2,
                'name'  => 'Topi Be Pop',
                'price' => 150000,
                'image' => 'assets/topi-pop.jpg',
                'sizes' => 'S - M - L - XL'
            ],
            [
                'id'    => 3,
                'name'  => 'Masker Kain Biru',
                'price' => 15000,
                'image' => 'assets/masker.jpg',
                'sizes' => 'S - M - L - XL'
            ],
            [
                'id'    => 4,
                'name'  => 'Jersey Bola Arsenal',
                'price' => 900000,
                'image' => 'assets/jersey-arsenal.jpg',
                'sizes' => 'S - M - L - XL'
            ],
        ];

        // Data untuk "Shop by Look"
        $shop_by_look_items = [
            $products[0], // Jersey Indonesia
            $products[3]  // Jersey Arsenal
        ];

        // Ambil data user yang sedang login
        $user = Auth::user();

        return view('homepage', compact('nav_links', 'categories', 'products', 'shop_by_look_items', 'user'));
    }
}