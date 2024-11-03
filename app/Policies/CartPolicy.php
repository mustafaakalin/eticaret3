<?php

namespace App\Policies;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CartPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
{
    // Tüm kullanıcılar kendi sepetlerini görüntüleyebilir
    return true;
}

public function view(User $user, Cart $cart): bool
{
    // Kullanıcı sepetin sahibiyse true döner
    return $cart->user_id === $user->id || $user->hasRole('admin');
}

public function create(User $user): bool
{
    // Tüm kullanıcılar sepet oluşturabilir
    return true;
}

public function update(User $user, Cart $cart): bool
{
    // Kullanıcı sepetin sahibiyse güncelleyebilir
    return $cart->user_id === $user->id || $user->hasRole('admin');
}

public function delete(User $user, Cart $cart): bool
{
    // Kullanıcı sepetin sahibiyse silebilir
    return $cart->user_id === $user->id || $user->hasRole('admin');
}


    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Cart $cart): bool
    {
        // Kullanıcı admin rolüne sahipse true döner, aksi takdirde false döner
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Cart $cart): bool
    {
        // Kullanıcı admin rolüne sahipse true döner, aksi takdirde false döner
        return $user->hasRole('admin');
    }
}
