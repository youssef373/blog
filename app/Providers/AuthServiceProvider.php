<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // Here, you can define policy classes for your application's models.
        // For example, if you have a `Post` model, you might create a `PostPolicy`
        // class to define the authorization logic for actions like creating,
        // updating, and deleting posts.
        // 'App\Models\Post' => 'App\Policies\PostPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();



        // Here, you can define the middleware that should be applied to your API routes
        // to ensure that incoming requests are properly authenticated.
        // For example, you might use the `auth:api` middleware to require a valid
        // access token for requests to your API.
        // Route::middleware('auth:api')->get('/user', function (Request $request) {
        //     return $request->user();
        // });
    }
}
