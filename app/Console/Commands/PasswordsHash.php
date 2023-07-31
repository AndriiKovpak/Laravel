<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PasswordsHash extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'passwords:hash';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hash plaintext passwords from temporary _Passwords table.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = DB::table('_Passwords')
            ->select('UserID', 'Password')
            ->get();

        foreach ($users as $user) {
            DB::table('_Passwords')
                ->where('UserID', $user->UserID)
                ->update([
                    'Hash' => bcrypt($user->Password),
                ]);
        }
    }
}
