<?php

namespace App\Policies;

use App\Models\Jamaah;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JamaahPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Jamaah $jamaah)
    {
        return $jamaah->referensi_id == $user->id || $user->hasRole('admin');
    }

    public function delete(User $user, Jamaah $jamaah)
    {
        return $jamaah->referensi_id == $user->id || $user->hasRole('admin');
    }
}
