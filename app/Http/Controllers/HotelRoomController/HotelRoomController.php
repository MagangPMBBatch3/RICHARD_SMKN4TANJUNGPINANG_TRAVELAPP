<?php

namespace App\Http\Controllers\HotelRoomController;

use App\Http\Controllers\Controller;

class HotelRoomController extends Controller
{
    public function index()
    {
        return view('hotelroom.index');
    }

    public function create()
    {
        return view('hotelroom.create');
    }

    public function manage($id)
    {
        return view('hotelroom.manage', compact('id'));
    }

    public function show($id)
    {
        return view('hotelroom.show', compact('id'));
    }

}
