<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WishList;

class WishListPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WishList $wishList): bool
    {
        return $user->id === $wishList->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WishList $wishList): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, WishList $wishList): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, WishList $wishList): bool
    {
        return false;
    }
}
