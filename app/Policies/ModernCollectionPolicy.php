<?php

namespace App\Policies;

use App\Models\ModernCollection;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ModernCollectionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->role->id >= 1;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ModernCollection  $modernCollection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, ModernCollection $modernCollection)
    {
        return $user->role->id >= 1;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->role->id >= 2;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ModernCollection  $modernCollection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ModernCollection $modernCollection)
    {
        return $user->role->id >= 2;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ModernCollection  $modernCollection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ModernCollection $modernCollection)
    {
        return $user->role->id >= 2;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ModernCollection  $modernCollection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ModernCollection $modernCollection)
    {
        return $user->role->id >= 2;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ModernCollection  $modernCollection
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ModernCollection $modernCollection)
    {
        return $user->role->id >= 2;
    }
}
