<?php

namespace App\Components\Core\Password;

use Closure;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Arr;

class PasswordBroker extends \Illuminate\Auth\Passwords\PasswordBroker
{
    /**
     * Reset the password for the given token.
     *
     * @param  array  $credentials
     * @param  \Closure  $callback
     * @return mixed
     */
    public function reset(array $credentials, Closure $callback)
    {
        // If the responses from the validate method is not a user instance, we will
        // assume that it is a redirect and simply return it from this method and
        // the user is properly redirected having an error message on the post.
        $user = $this->validateReset($credentials);

        if (! $user instanceof CanResetPasswordContract) {
            return $user;
        }

        $password = $credentials['Password'];

        // Once the reset has been validated, we'll call the given callback with the
        // new password. This gives the user an opportunity to store the password
        // in their persistent storage. Then we'll delete the token and return.
        $callback($user, $password);

        $this->tokens->delete($user);

        return static::PASSWORD_RESET;
    }

    /**
     * Get the user for the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\CanResetPassword
     *
     * @throws \UnexpectedValueException
     */
    public function getUser(array $credentials)
    {
        if (Arr::has($credentials, 'Token')) {

            $credentials = [
                'UserID'    =>  $this->tokens->getUserIDFromToken(Arr::get($credentials, 'Token'))
            ];
        }

        $user = $this->users->retrieveByCredentials($credentials);

        if ($user && ! $user instanceof CanResetPasswordContract) {
            throw new \UnexpectedValueException('User must implement CanResetPassword interface.');
        }

        return $user;
    }

    /**
     * Determine if the passwords match for the request.
     *
     * @param  array  $credentials
     * @return bool
     */
    public function validateNewPassword(array $credentials)
    {
        if (isset($this->passwordValidator)) {
            list($password, $confirm) = [
                $credentials['Password'],
                $credentials['PasswordConfirmation'],
            ];

            return call_user_func(
                    $this->passwordValidator, $credentials
                ) && $password === $confirm;
        }

        return $this->validatePasswordWithDefaults($credentials);
    }

    /**
     * Determine if the passwords are valid for the request.
     *
     * @param  array  $credentials
     * @return bool
     */
    protected function validatePasswordWithDefaults(array $credentials)
    {
        list($password, $confirm) = [
            $credentials['Password'],
            $credentials['PasswordConfirmation'],
        ];

        return $password === $confirm && mb_strlen($password) >= 6;
    }

    /**
     * Validate a password reset for the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\CanResetPassword|string
     */
    protected function validateReset(array $credentials)
    {
        if (is_null($user = $this->getUser($credentials))) {
            return static::INVALID_TOKEN;
        }

        if (! $this->validateNewPassword($credentials)) {
            return static::INVALID_PASSWORD;
        }

        if (! $this->tokens->exists($user, $credentials['Token'])) {
            return static::INVALID_TOKEN;
        }

        return $user;
    }
}