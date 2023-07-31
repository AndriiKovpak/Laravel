<?php

/**
 * Created by PhpStorm.
 * User: bcooper
 * Date: 3/2/2017
 * Time: 3:51 PM
 */

namespace App\Services;

use App\Models\DivisionDistrict;
use App\Models\IPLocation;
use App\Models\UsersDivisionDistrict;
use App\Models\User;
use App\Models\Session;
use Illuminate\Support\Facades\DB;


class UsersService
{

    public function getUsers($session, $request)
    {
        $this->sort($session, $request);
        $users = User::select('Users.UserID', 'Users.FirstName', 'Users.LastName', 'Users.SecurityGroup')->where('UserStatus', 1);
        if (trim($session->get('UsersSort'))) {
            $users->where('SecurityGroup', trim($session->get('UsersSort')));
        } else if (trim($session->get('UsersSearch'))) {
            $users->where('Users.FirstName', 'like', '%' . trim($session->get('UsersSearch')) . '%')
                ->orWhere('Users.LastName', 'like', '%' . trim($session->get('UsersSearch')) . '%')
                ->orWhere('Users.UserName', 'like', '%' . trim($session->get('UsersSearch')) . '%')
                ->orWhere('Users.EmailAddress', 'like', '%' . trim($session->get('UsersSearch')) . '%');
            $session->put(['UsersSort' => 3]);
        } else {
            $users->where('SecurityGroup', 1);
        }

        $admin = User::where('SecurityGroup', 1)->where('UserStatus', 1)->orderBy('LastName', 'asc')->get();
        $reporting = User::where('SecurityGroup', 2)->where('UserStatus', 1)->orderBy('LastName', 'asc')->get();
        return ([$users->orderBy('LastName', 'asc')->paginate(20), $admin->count(), $reporting->count()]);
    }

    private function sort($session, $request)
    {
        if ($request->input('UsersSearch')) {
            $session->put(['UsersSearch' => $request->input('UsersSearch')]);
            $session->put(['UsersSort' => null]);
        }
        if ($request->input('ListBy')) {
            $session->put(['UsersSort' => $request->input('ListBy')]);
            $session->put(['UsersSearch' => null]);
        }
        if ($session->get('UsersSort') == 3) {
            $session->put(['UsersSort' => 1]);
        }
    }

    /*
     * Update existing user
     */
    public function updateUser($id, $request)
    {
        $user = User::findOrFail($id);

        //Update Division Districts
        foreach ([$user->DivisionDistricts()->get()][0] as $district) {
            $dd = DivisionDistrict::findorFail($district['DivisionDistrictID']);
            $dd->Users()->detach($id);
        }
        if ($request->to) {
            foreach ($request->to as $newDistrictID) {
                $dd = DivisionDistrict::find($newDistrictID);
                $dd->Users()->attach($id);
            }
        }

        $user->update($request->data(true));
    }

    /*
     * Create a new user
     */
    public function createUser($request)
    {
        $user = User::create($request->data(true));

        if ($request->to) {
            foreach ($request->to as $newDistrictID) {
                $dd = DivisionDistrict::find($newDistrictID);
                $dd->Users()->attach($user->UserID);
            }
        }
    }


    public function sortDistricts($all, $selected)
    {
        $sorted = [];
        foreach ($all as $id => $value) {
            if ($this->checkDistrict($id, $selected)) {
                array_push($sorted, ['id' => $id, 'name' => $value]);
            }
        }
        return $sorted;
    }

    private function checkDistrict($id, $selected)
    {
        foreach ($selected as $distr) {
            if ($distr['DivisionDistrictID'] == $id) {
                return  false;
            }
        }
        return true;
    }

    //TODO: I don't like how this is done. Will be slow when there are lots of entries.
    //TODO: Better way to do this?
    /*
     * Converts the IP to BIGINT, then queries the IPLocations table to get the text location name for an IP Address
     */
    public function getLocation($ip)
    {
        if ($ip === '127.0.0.1') {
            return 'localhost';
        }
        $ip = $this->IPtoBigInt($ip);
        $location = DB::select('SELECT Region, City, Country_Name FROM IPLocations WHERE ? BETWEEN IP_From AND IP_To', [$ip]);
        $location = json_decode(json_encode($location[0]), true);
        if ($location['City'] == '-') {
            return 'Unknown Location';
        } else {
            return $location['City'] . ', ' . $location['Region'] . ' (' . $location['Country_Name'] . ')';
        }
    }

    /*
     * Convert IP Address to BIGINT for sql between
     */
    private function IPtoBigInt($ip)
    {
        $ipArr    = explode('.', $ip);
        $ip       = $ipArr[0] * 0x1000000
            + $ipArr[1] * 0x10000
            + $ipArr[2] * 0x100
            + $ipArr[3];
        return $ip;
    }
}
