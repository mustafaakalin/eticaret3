<?php

namespace App\Policies;

use App\Models\Product_tag;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class Product_tagPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Kullanıcı admin rolüne sahipse true döner, aksi takdirde false döner
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Product_tag $productTag): bool
    {
        // Kullanıcı admin rolüne sahipse true döner, aksi takdirde false döner
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Kullanıcı admin rolüne sahipse true döner, aksi takdirde false döner
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Product_tag $productTag): bool
    {
        // Kullanıcı admin rolüne sahipse true döner, aksi takdirde false döner
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Product_tag $productTag): bool
    {
        // Kullanıcı admin rolüne sahipse true döner, aksi takdirde false döner
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Product_tag $productTag): bool
    {
        // Kullanıcı admin rolüne sahipse true döner, aksi takdirde false döner
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Product_tag $productTag): bool
    {
        // Kullanıcı admin rolüne sahipse true döner, aksi takdirde false döner
        return $user->hasRole('admin');
    }
}
