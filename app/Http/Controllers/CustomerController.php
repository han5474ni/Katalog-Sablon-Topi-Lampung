<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display customer dashboard
     */
    public function dashboard()
    {
        try {
            if (!auth()->check()) {
                return redirect()->route('login');
            }
            
            return view('customer.dashboard', [
                'user' => auth()->user()
            ]);
        } catch (\Exception $e) {
            \Log::error('Dashboard Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Unable to access dashboard. Please try again.');
        }
    }

    /**
     * Display shopping cart page
     */
    public function keranjang()
    {
        return view('customer.keranjang');
    }

    /**
     * Display order list page
     */
    public function orderList()
    {
        return view('customer.order-list');
    }

    /**
     * Display chatbot page
     */
    public function chatbot()
    {
        return view('customer.chatbot');
    }
}
