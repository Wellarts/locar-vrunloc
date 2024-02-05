<?php

namespace App\Policies;

use App\Models\Locacao;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LocacaoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('View Locacao');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Locacao $locacao)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Create Locacao');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Locacao $locacao): bool
    {
        return $user->hasPermissionTo('Update Locacao');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Locacao $locacao): bool
    {
        return $user->hasPermissionTo('Delete Locacao');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Locacao $locacao)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Locacao $locacao)
    {
        //
    }
}
