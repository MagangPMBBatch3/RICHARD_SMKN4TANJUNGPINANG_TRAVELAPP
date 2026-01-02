<?php

namespace App\Http\Controllers\HotelController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function show($id)
    {
        return view('hotel.show', compact('id'));
    }

    public function index(Request $request)
    {
    $search = $request->query('search');

    $hotels = \App\Models\Hotel\Hotel::query()
        ->when($search, function ($q) use ($search) {
            $q->where('name', 'like', "%$search%")
              ->orWhere('location', 'like', "%$search%")
              ->orWhere('description', 'like', "%$search%");
        })
        ->get();

    return view('hotel.index', compact('hotels', 'search'));
    }

}
