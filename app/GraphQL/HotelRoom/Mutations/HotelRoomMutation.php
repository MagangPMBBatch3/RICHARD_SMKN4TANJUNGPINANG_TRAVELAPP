<?php

namespace App\GraphQL\HotelRoom\Mutations;

use App\Models\HotelRoom\HotelRoom;
use App\Models\HotelRoomPhoto\HotelRoomPhoto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Exception;

class HotelRoomMutation
{
    /**
     * Create Hotel Room with Photos (base64)
     */
    public function createHotelRoomWithPhotos($_, array $args)
    {
        DB::beginTransaction();
        try {
            $input = $args['input'];

            // Simpan data hotel room utama
            $room = HotelRoom::create([
                'hotel_id' => $input['hotel_id'],
                'room_type_id' => $input['room_type_id'],
                'name' => $input['name'],
                'price' => $input['price'],
                'quantity' => $input['quantity'] ?? 0,
            ]);

            // Simpan foto (kalau ada)
            if (!empty($input['photos'])) {
                foreach ($input['photos'] as $b64) {
                    $photoPath = $this->saveBase64Photo($b64, 'hotel_rooms');
                    HotelRoomPhoto::create([
                        'hotel_room_id' => $room->id,
                        'photo' => $photoPath,
                    ]);
                }
            }

            DB::commit();
            return $room;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Gagal membuat kamar: " . $e->getMessage());
        }
    }

    /**
     * Update Hotel Room
     */
    public function updateHotelRoom($_, array $args)
    {
        $room = HotelRoom::findOrFail($args['id']);
        $input = $args['input'];

        $room->update([
            'hotel_id' => $input['hotel_id'] ?? $room->hotel_id,
            'room_type_id' => $input['room_type_id'] ?? $room->room_type_id,
            'name' => $input['name'] ?? $room->name,
            'price' => $input['price'] ?? $room->price,
            'quantity' => $input['quantity'] ?? $room->quantity,
        ]);

        return $room;
    }

    /**
     * Delete Hotel Room (soft delete)
     */
    public function deleteHotelRoom($_, array $args)
    {
        $room = HotelRoom::findOrFail($args['id']);
        $room->delete();
        return $room;
    }

    /**
     * Restore soft deleted Hotel Room
     */
    public function restore($_, array $args)
    {
        $room = HotelRoom::withTrashed()->findOrFail($args['id']);
        $room->restore();
        return $room;
    }

    /**
     * Permanently delete Hotel Room
     */
    public function forceDelete($_, array $args)
    {
        $room = HotelRoom::withTrashed()->findOrFail($args['id']);
        $room->forceDelete();
        return $room;
    }

    /**
     * Helper: Simpan foto base64 ke storage/app/public
     */
    private function saveBase64Photo($base64, $folder = 'hotel_rooms')
    {
        // Contoh: data:image/png;base64,xxxxx
        if (preg_match('/^data:image\/(\w+);base64,/', $base64, $type)) {
            $image = substr($base64, strpos($base64, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, gif
            $image = base64_decode($image);
            $fileName = uniqid() . '.' . $type;
            $filePath = $folder . '/' . $fileName;
            Storage::disk('public')->put($filePath, $image);
            return $filePath;
        }
        return null;
    }
}
