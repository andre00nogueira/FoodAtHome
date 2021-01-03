<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class UserPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return true;
    }

    public function view(User $user, User $curr)
    {
        return $user->id === $curr->id;
    }

    /*public function edit(User $user, Post $post)
    {
        return $user->id == $post->user_id;
    }

    public function update(User $user, Post $post)
    {
        return $user->id == $post->user_id;
    }

    public function delete(User $user, Post $post)
    {
        return $user->id == $post->user_id;
    }*/
}
