<?php

namespace App\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Session;

/**
 * Auth::check()/Auth::user() backed by the session-stored JWT + user payload
 * we get from users-service — no local users table, see docs/architecture.md.
 */
class SessionJwtGuard implements Guard
{
    private ?SessionUser $user = null;

    public function check(): bool
    {
        return Session::has('jwt');
    }

    public function guest(): bool
    {
        return ! $this->check();
    }

    public function user(): ?Authenticatable
    {
        if (! $this->check()) {
            return null;
        }

        return $this->user ??= new SessionUser(Session::get('user'));
    }

    public function id()
    {
        return $this->user()?->getAuthIdentifier();
    }

    public function validate(array $credentials = []): bool
    {
        return false;
    }

    public function hasUser(): bool
    {
        return $this->user() !== null;
    }

    public function setUser(Authenticatable $user): void
    {
        //
    }
}
