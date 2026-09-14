<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\NewUserNotification;
use App\Services\AffiliateService;
use App\Services\UserService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
        private readonly AffiliateService $affiliateService,
    ) {}

    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'ref' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $data['ref'] = $data['ref'] ?? null;

        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ];

        if (!empty($data['ref'])) {
            $userData['inviter'] = (int) $data['ref'];
        }

        $user = $this->userService->createUser($userData);

        event(new Registered($user));
        Auth::login($user);

        $admins = User::role('admin')->get();
        Notification::send($admins, new NewUserNotification($user->name, $user->email));

        if ($request->ajax()) {
            return response()->json(['status' => true, 'redirect' => url('/')]);
        }

        return redirect('/');
    }
}
