<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * GOOGLE LOGIN
     */
    // public function redirectToGoogle()
    // {
    //     return Socialite::driver('google')->redirect();
    // }

    // public function handleGoogleCallback()
    // {
    //     $googleUser = Socialite::driver('google')->stateless()->user();

    //     $user = User::updateOrCreate(
    //         [
    //             'email' => $googleUser->getEmail(),
    //         ],
    //         [
    //             'name' => $googleUser->getName(),
    //             'provider' => 'google',
    //             'provider_id' => $googleUser->getId(),
    //             'avatar' => $googleUser->getAvatar(),
    //             'password' => bcrypt(Str::random(16)),
    //         ]
    //     );

    //     Auth::login($user);

    //     return response()->json([
    //         'message' => 'Google Login Success',
    //         'user' => $user
    //     ]);
    // }

    /**
     * GITHUB LOGIN
     */
    public function redirectToGithub()
    {
        return Socialite::driver('github')
            ->scopes(['read:user', 'user:email'])
            ->redirect();
    }

    public function handleGithubCallback()
    {
        $githubUser = Socialite::driver('github')->user();
        $email = $githubUser->getEmail() ?? 'github_'.$githubUser->getId().'@users.noreply.github.com';
        $name = $githubUser->getName() ?? $githubUser->getNickname() ?? 'GitHub User';

        $user = User::updateOrCreate(
            [
                'provider' => 'github',
                'provider_id' => $githubUser->getId(),
            ],
            [
                'name' => $name,
                'email' => $email,
                'username' => $githubUser->getNickname(),
                'avatar' => $githubUser->getAvatar(),
                'password' => bcrypt(Str::random(16)),
            ]
        );

        Auth::login($user);

        request()->session()->regenerate();

        return redirect()->intended(route('home'));
    }
}
