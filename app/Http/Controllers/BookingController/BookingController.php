<?php

namespace App\Http\Controllers\BookingController;

use App\Http\Controllers\Controller;

class BookingController extends Controller
{
    public function index()
    {
        return view('booking.history');
    }

    public function show($id)
    {
        return view('booking.detail', compact('id'));
    }

}
