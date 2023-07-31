<?php

namespace App\Components\Core\Password;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class DatabaseTokenRepository extends \Illuminate\Auth\Passwords\DatabaseTokenRepository
{
    /**
     * Create a new token record.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword $user
     * @return string
     */
    public function create(CanResetPasswordContract $user)
    {
        $this->deleteExisting($user);

        // We will create a new, random token for the user so that we can e-mail them
        // a safe link to the password reset form. Then we will insert a record in
        // the database so that we can verify the token within the actual reset.
        $token = $this->createNewToken();

        $now = new Carbon;

        $this->getTable()->insert([
            'UserID' => $user->getKey(),
            'Token' => $this->hasher->make($token),
            'IssueDate' => $now->toDateTimeString(),
            'Created_at' => $now->toDateTimeString(),
            'Updated_at' => $now->toDateTimeString(),
            'ExpireDate' => $now->addSeconds($this->expires)->toDateTimeString()
        ]);

        return $token;
    }

    /**
     * Delete all existing reset tokens from the database.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword $user
     * @return int
     */
    protected function deleteExisting(CanResetPasswordContract $user)
    {
        return $this->getTable()->where('UserID', $user->getKey())->delete();
    }

    /**
     * Determine if a token record exists and is valid.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword $user
     * @param  string $token
     * @return bool
     */
    public function exists(CanResetPasswordContract $user, $token)
    {
        $record = (array)$this->getTable()->where(
            'UserID', $user->getKey()
        )->first();

        return $record &&
            !$this->tokenExpired($record['ExpireDate']) &&
            $this->hasher->check($token, $record['Token']);
    }

    /**
     * Return UserID from the tokens table
     *
     * @param $token
     * @return array|null|\stdClass
     */
    public function getUserIDFromToken($token)
    {
        $result = null;
        $data = $this->getTable()->select('UserID', 'Token', 'ExpireDate')->get();
        if (count($data) > 0) {
            foreach ($data as $record) {
                if (!$this->tokenExpired($record->ExpireDate) && $this->hasher->check($token, $record->Token))
                    return intval($record->UserID);
            }
        }

        return null;
    }

    /**
     * Determine if the token has expired.
     *
     * @param  string $createdAt
     * @return bool
     */
    protected function tokenExpired($createdAt)
    {
        return Carbon::parse($createdAt)->isPast();
    }

    /**
     * Delete expired tokens.
     *
     * @return void
     */
    public function deleteExpired()
    {
        $this->getTable()->where('ExpireDate', '>', Carbon::now())->delete();
    }
}