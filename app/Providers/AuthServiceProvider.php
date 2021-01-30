<?php

namespace App\Providers;

use App\Services\Auth\Contracts\Service as AuthServiceContract;
use App\Services\Auth\Service as AuthService;
use Illuminate\Config\Repository;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Lcobucci\JWT\Configuration as TokenConfiguration;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->bindTokenConfiguration();
        $this->bindAuthService();
    }

    /**
     * Bind auth service.
     *
     * @return void
     */
    protected function bindAuthService(): void
    {
        $this->app->alias(AuthServiceContract::class, AuthService::class);

        $this->app->bind(AuthServiceContract::class, function ($app) {
            return new AuthService(
                new Repository($app->config->get('services.auth')),
                $app->make(TokenConfiguration::class)
            );
        });
    }

    /**
     * Bind JWT token configuration.
     *
     * @return void
     */
    protected function bindTokenConfiguration(): void
    {
        $this->app->bind(TokenConfiguration::class, function () {
            // in real world apps use more secure options
            // with signer and keys
            // I promise :)
            return TokenConfiguration::forUnsecuredSigner();
        });
    }
}
