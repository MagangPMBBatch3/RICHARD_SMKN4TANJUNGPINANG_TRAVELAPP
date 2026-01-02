<?php

namespace App\GraphQL\User\Queries;

use App\Models\User;

class UserQuery
{
    /**
     * Mendapatkan semua user (bisa ditambahkan search filter)
     */
    public function getUsers($_, array $args)
    {
        $query = User::query();

        if (!empty($args['search'])) {
            $search = $args['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $query->get();
    }
}
