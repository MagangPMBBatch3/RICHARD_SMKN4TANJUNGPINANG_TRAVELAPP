<?php

namespace App\Http\Controllers\TransportController;

use App\Http\Controllers\Controller;
use App\Models\Transport\Transport;
use Illuminate\Http\Request;

class TransportController extends Controller
{
    public function index()
    {
        $transports = Transport::with('schedules')
            ->latest()
            ->get();

        return view('transport.index', compact('transports'));
    }

    public function show($id)
    {
        $transport = Transport::with('schedules')
            ->findOrFail($id);

        return view('transport.show', compact('transport'));
    }

    public function locations()
    {
        return view('transport.locations.index');
    }
}
