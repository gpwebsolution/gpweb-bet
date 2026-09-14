<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
    ) {}

    public function redirectToProvider(string $driver)
    {
        return Socialite::driver($driver)->redirect();
    }

    public function handleProviderCallback(string $driver)
    {
        $socialUser = Socialite::driver($driver)->user();

        $user = $this->userService->findOrCreateGoogleUser([
            'id' => $socialUser->getId(),
            'name' => $socialUser->getName(),
            'email' => $socialUser->getEmail(),
            'avatar' => $socialUser->getAvatar(),
        ]);

        auth()->login($user, true);

        return redirect()->intended('/');
    }
}
