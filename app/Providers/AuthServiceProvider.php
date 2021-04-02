<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Lcobucci\JWT\UnencryptedToken;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function (Request $request) {
            $header = $request->header('Authorization');

            if ($header) {
                try {
                    $apiToken = explode(' ', $header)[1];
                    $auth = Firebase::auth();
                    $verifiedToken = $auth->verifyIdToken($apiToken);

                    if ($verifiedToken instanceof UnencryptedToken) {
                        $uid = $verifiedToken->claims()->get('sub');
                        return User::where('uid', $uid)->first();
                    }
                } catch (\Exception $e) {
                }
            }
        });
    }
}
