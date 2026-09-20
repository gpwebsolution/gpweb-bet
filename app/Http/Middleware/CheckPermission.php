<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

class CheckPermission
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    private $status = false;

    /**
     * Create a new middleware instance.
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next, $roles)
    {
        if (\Auth::check()) {
            if (strripos($roles, '|')) {
                $roles = explode('|', $roles);
                if (in_array(\Auth::user()->role_id, $roles)) {
                    $this->status = true;
                }
            } elseif (! empty($roles)) {
                if (\Auth::user()->role_id == $roles) {
                    $this->status = true;
                }
            }

            if ($this->status) {
                return $next($request);
            } else {
                \Auth::logout();

                return back()->with('error', 'Você não tem permissão para acessar essa área!');
            }
        } else {
            return redirect()->guest('/');
        }
    }
}
