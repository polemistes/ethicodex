<?php

namespace App\Policies;

use App\Models\TransactionParty;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPartyPolicy
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
        return $user->role->id >= 2;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TransactionParty  $transactionParty
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, TransactionParty $transactionParty)
    {
        return $user->role->id >= 2;
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
     * @param  \App\Models\TransactionParty  $transactionParty
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, TransactionParty $transactionParty)
    {
        return $user->role->id >= 2;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TransactionParty  $transactionParty
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, TransactionParty $transactionParty)
    {
        return $user->role->id >= 2;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TransactionParty  $transactionParty
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, TransactionParty $transactionParty)
    {
        return $user->role->id >= 2;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TransactionParty  $transactionParty
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, TransactionParty $transactionParty)
    {
        return $user->role->id >= 2;
    }
}
