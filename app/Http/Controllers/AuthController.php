<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Contracts\User as SocialUser;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Keycloak\Provider as KeycloakProvider;

class AuthController extends Controller
{
    public function login(): View|Factory
    {
        return view('login');
    }

    public function redirect()
    {
        return Socialite::driver('keycloak')->redirect();
    }

    public function callback(): Redirector|RedirectResponse
    {
        /** @var KeycloakProvider */
        $driver = Socialite::driver('keycloak');

        /** @var SocialUser */
        $keycloakUser = $driver->user();

        $count = User::count();
        $user = User::find($keycloakUser->getId());
        if ($user == null) {
            $user = new User();
            $user->id = $keycloakUser->getId();
            $user->name = $keycloakUser->getName();
            $user->email = $keycloakUser->getEmail();
            $user->provider = 'keycloak';
            $user->markEmailAsVerified();
            $user->save();
        }

        // assign Admin role to the first user
        if ($count == 0)
        {
            $user->assignRole(Role::Admin);
        }

        Auth::login($user);

        return redirect('/admin');
    }

    public function logout(): Redirector|RedirectResponse
    {
        Auth::logout();

        // The URL the user is redirected to after logout.
        $redirectUri = config('app.url');

        /** @var KeycloakProvider */
        $driver = Socialite::driver('keycloak');

        // Keycloak v18+ does support a post_logout_redirect_uri in combination with a
        // client_id or an id_token_hint parameter or both of them.
        // NOTE: You will need to set valid post logout redirect URI in Keycloak.
        return redirect($driver->getLogoutUrl($redirectUri, config('services.keycloak.client_id')));
    }
}
