<?php

namespace App\Policies;

use App\Models\Marca;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MarcaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('View Marca');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Marca $marca)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Create Fornecedor');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Marca $marca): bool
    {
        return $user->hasPermissionTo('Update Fornecedor');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Marca $marca): bool
    {
        return $user->hasPermissionTo('Delete Fornecedor');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Marca $marca)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Marca $marca)
    {
        //
    }
}
