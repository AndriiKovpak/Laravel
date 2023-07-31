<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UsersSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncs up passwords for users loaded from old database.';

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
        $r = $user_info = DB::table('Users')
            ->join('egs_db.dbo.UserLogins', 'Users.UserID', '=', 'UserLogins.UserLoginID')
            ->whereRaw('egs_db.dbo.UserLogins.UserName = Users.UserName')
            ->select('Users.UserID', 'Users.UserName', DB::raw('egs_db.dbo.fn_decrypt_des3(UserLogins.Password) as Password'))
            ->get();
        print(count($r) . " matching users found.\n");

        $updated = 0;
        if (count($r) > 0) {
            print("Processing...\n");

            foreach ($r as $row) {
                $user = User::find($row->UserID);

                if ($user == null) {
                    print("Unable to process user " . $row->UserID . " - " . $row->UserName . ". Missing from new DB.\n");
                    continue;
                }

                try {
                    $user->setAttribute('Password', $row->Password);
                    $user->save();
                    $updated++;
                } catch (\Exception $e) {
                    print("Unable to process user " . $row->UserID . " - " . $row->UserName . ". " . $e->getMessage() . "\n");
                    continue;
                }
            }
        }

        print($updated . " users synced.\n");
    }
}
