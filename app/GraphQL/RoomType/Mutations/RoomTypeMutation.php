<?php

namespace App\GraphQL\RoomType\Mutations;

use App\Models\RoomType\RoomType;

class RoomTypeMutation
{
    public function restore($_, array $args): ?RoomType
    {
        return RoomType::withTrashed()->find($args['id'])?->restore()
            ? RoomType::find($args['id'])
            : null;
    }

    public function forceDelete($_, array $args): ?RoomType
    {
        $type = RoomType::withTrashed()->find($args['id']);
        if ($type) {
            $type->forceDelete();
            return $type;
        }
        return null;
    }
}
