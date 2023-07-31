<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class UsersMock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:mock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates some mock users for testing purposes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Add mock users
        $user = User::where('UserName','usace_user')->get();
        if(count($user) == 0) {
            $user = User::create([
                'FirstName' => 'USACE',
                'LastName' => 'User',
                'UserName' => 'usace_user',
                'EmailAddress' => 'joe@example.com',
                'Password' => 'Password1',
                'SecurityGroup' => 1,
                'UserStatus' => 1
            ]);
            print("Created usace_user.\n");
        }

        $user = User::where('UserName','reporting_user')->get();
        if(count($user) == 0) {
            $user = User::create([
                'FirstName' => 'Reporting',
                'LastName' => 'User',
                'UserName' => 'reporting_user',
                'EmailAddress' => 'brandon@example.com',
                'Password' => 'Password1',
                'SecurityGroup' => 2,
                'UserStatus' => 1
            ]);
            print("Created reporting_user.\n");
        }

        print("All users created.\n");
    }
}
