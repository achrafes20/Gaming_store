<?php

namespace App\Auth;

use Illuminate\Contracts\Auth\Authenticatable;

/**
 * Lightweight stand-in for an Eloquent User model, built from the
 * user object returned by users-service (see AuthController::login).
 * web-bff owns no user data locally.
 */
class SessionUser implements Authenticatable
{
    public function __construct(private object $data) {}

    public function __get($name)
    {
        return $this->data->{$name} ?? null;
    }

    public function hasVerifiedEmail(): bool
    {
        return true;
    }

    public function favorites(): FavoritesQuery
    {
        return new FavoritesQuery();
    }

    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return $this->data->id;
    }

    public function getAuthPasswordName()
    {
        return 'password';
    }

    public function getAuthPassword()
    {
        return '';
    }

    public function getRememberToken() {}

    public function setRememberToken($value) {}

    public function getRememberTokenName()
    {
        return '';
    }
}
