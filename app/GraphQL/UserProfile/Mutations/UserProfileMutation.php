<?php

namespace App\GraphQL\UserProfile\Mutations;

use App\Models\UserProfile\UserProfile;

class UserProfileMutation
{
    public function restore($_, array $args): ?UserProfile
    {
        return UserProfile::withTrashed()->find($args['id'])?->restore()
            ? UserProfile::find($args['id'])
            : null;
    }

    public function forceDelete($_, array $args): ?UserProfile
    {
        $profile = UserProfile::withTrashed()->find($args['id']);
        if ($profile) {
            $profile->forceDelete();
            return $profile;
        }
        return null;
    }
}
