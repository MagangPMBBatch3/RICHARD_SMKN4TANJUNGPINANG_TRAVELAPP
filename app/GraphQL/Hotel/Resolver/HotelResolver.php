<?php

namespace App\GraphQL\Hotel\Resolver;

use App\Models\Hotel\Hotel;
use App\Models\HotelRoom\HotelRoom;
use Illuminate\Support\Facades\Storage;

class HotelResolver
{
public function photoUrl(Hotel $hotel)
    {
        return $hotel->photo
            ? asset('storage/' . $hotel->photo)
            : asset('images/no-image.jpg');
    }

    public function maxRoomPrice(Hotel $hotel, array $args)
    {
        return HotelRoom::where('hotel_id', $hotel->id)->max('price');
    }

    public function create($_, array $args)
    {
        $input = $args['input'];

        if (isset($input['photo']) && $input['photo']) {
            $path = $input['photo']->store('hotel', 'public');
            $input['photo'] = $path;
        }
        return Hotel::create($input);
    }

    public function update($_, array $args)
    {
        $input = $args['input'];
        $hotel = Hotel::findOrFail($input['id']);

        if (isset($input['photo']) && $input['photo']) {
            if ($hotel->photo) {
                Storage::disk('public')->delete($hotel->photo);
            }
            $path = $input['photo']->store('hotel', 'public');
            $input['photo'] = $path;
        }

        $hotel->update($input);
        return $hotel;
    }

    public function delete($_, array $args)
    {
        $hotel = Hotel::findOrFail($args['id']);

        if ($hotel->photo) {
            Storage::disk('public')->delete($hotel->photo);
        }

        return $hotel->delete();
    }

    public function all($_, array $args)
    {
        return Hotel::all();
    }

    public function find($_, array $args)
    {
        return Hotel::findOrFail($args['id']);
    }
}
