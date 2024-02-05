<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Veiculo;
use Illuminate\Auth\Access\Response;

class VeiculoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('View Veiculo');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Veiculo $veiculo)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Create Veiculo');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Veiculo $veiculo): bool
    {
        return $user->hasPermissionTo('Update Veiculo');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Veiculo $veiculo): bool
    {
        return $user->hasPermissionTo('Delete Veiculo');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Veiculo $veiculo)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Veiculo $veiculo)
    {
        //
    }
}
