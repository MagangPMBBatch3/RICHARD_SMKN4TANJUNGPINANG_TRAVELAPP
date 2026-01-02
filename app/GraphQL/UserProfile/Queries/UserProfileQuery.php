<?php

namespace App\GraphQL\UserProfile\Queries;

use App\Models\UserProfile\UserProfile;

class UserProfileQuery
{
    public function all($_, array $args)
    {
        return UserProfile::all();
    }
}
