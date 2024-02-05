<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Cliente;
use App\Models\ContasPagar;
use App\Models\ContasReceber;
use App\Models\FluxoCaixa;
use App\Models\Fornecedor;
use App\Models\User;
use App\Policies\ActivityPolicy;
use App\Policies\ClientePolicy;
use App\Policies\ContasPagarPolicy;
use App\Policies\ContasReceberPolicy;
use App\Policies\FluxoCaixaPolicy;
use App\Policies\FornecedorPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Cliente::class => ClientePolicy::class,
        Activity::class => ActivityPolicy::class,
        ContasPagar::class => ContasPagarPolicy::class,
        ContasReceber::class => ContasReceberPolicy::class,
        FluxoCaixa::class => FluxoCaixaPolicy::class,
        Fornecedor::class => FornecedorPolicy::class,
        Permission::class => PermissionPolicy::class,
        Role::class => RolePolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
