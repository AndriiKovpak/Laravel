<?php

/**
 * Created by PhpStorm.
 * User: bcooper
 * Date: 3/2/2017
 * Time: 3:40 PM
 */

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UpdateRequest;
use App\Models\DivisionDistrict;
use App\Models\SecurityGroup;
use App\Models\Session;
use App\Models\User;
use App\Models\UserStatusType;
use App\Services\UsersService;
use Carbon\Carbon;
use DateTime;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Auth\Passwords\TokenRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    private $message = "Not Defined";

    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    /**
     * Index
     * @param Request $request
     * @param UsersService $UsersService
     * @return view
     */
    public function index(Request $request, UsersService $UsersService)
    {
        //1 - admin, 2 - reporting
        $session = $request->session();
        $users = $UsersService->getUsers($session, $request);
        return view('dashboard.users.index', [
            'users' => $users[0],
            'adminUsers' => $users[1],
            'reportingUsers' => $users[2],
            'searchString' => $request->get('UsersSearch', ''),
            'ListBy' => $session->get('UsersSort') ?? $request->get('ListBy'),
        ]);
    }

    /**
     * Session
     * @param Request $request
     * @param $id
     * @return redirect
     */
    public function session(Request $request, $id, $from)
    {
        $user = User::find($id);
        //TODO: if a user is logged in and they refresh, it will not let them login the first time they try, but they will be find if they try a second time
        //Set the SessionRefresh flag for the user, so that the next request sent will log them out.
        $user->SessionRefresh = true;
        $user->save();

        if ($from == 'edit') {
            return redirect()
                ->route('dashboard.users.show', $id)
                ->with('notification.success', $user->FirstName . ' ' . $user->LastName . "'s session has been reset.");
        } else if ($from == 'index') {
            return redirect()
                ->route('dashboard.users.index', ['UsersSearch' => $request->session()->get('UsersSearch')])
                ->with('notification.success', $user->FirstName . ' ' . $user->LastName . "'s session has been reset.");
        }
    }

    /**
     * History
     * @param Request $request
     * @param $id
     * @return view
     */
    public function history(Request $request, $id, UsersService $UsersService)
    {
        $user = User::find($id);
        $history = $user->Sessions()->orderBy('Sessions_BeginDate', 'desc');
        return view('dashboard.users.history', [
            'history' => $history->paginate(15),
            'name' => $user->FirstName . ' ' . $user->LastName,
            'UsersService' => $UsersService,
        ]);
    }

    /**
     * show
     * @param Request $request
     * @param id
     * @param UsersService $UsersService
     * @return view
     */
    public function show(Request $request, $id, UsersService $UsersService)
    {
        $User = User::find($id);
        return view('dashboard.users.show', [
            'user' => $User,
            'message' => $this->message,
            'districts' => $User->DivisionDistricts()->get(),
        ]);
    }

    /**
     * Get Edit
     * @param Request $request
     * @param id
     * @param UsersService $UsersService
     * @return view
     */
    public function edit(Request $request, $id, UsersService $UsersService)
    {
        $user = User::find($id);
        $otherDistricts = $UsersService->sortDistricts(DivisionDistrict::getOptionsForSelect(), $user->DivisionDistricts()->get());
        return view('dashboard.users.edit', [
            'user' => $user,
            'statuses' => UserStatusType::all(),
            'securityGroup' => SecurityGroup::active()->get(),
            'message' => $this->message,
            'selectedDistricts' => $user->DivisionDistricts()->get(),
            'otherDistricts' => $otherDistricts,
            'allDistricts' => DivisionDistrict::getOptionsForSelect(),
        ]);
    }

    /**
     * Update
     * @param Request $request
     * @param id
     * @param UsersService $UsersService
     * @return view
     */
    public function update(UpdateRequest $request, $id, UsersService $UsersService)
    {
        $UsersService->updateUser($id, $request);
        //updateUser
        if (Auth::user()->refresh()->can('edit')) {
            return redirect()
                ->route('dashboard.users.show', $id)
                ->with('notification.success', 'User Information was successfully updated.');
        } else {
            return redirect()
                ->route('dashboard.home.index')
                ->with('notification.success', 'User Information was successfully updated.');
        }
    }

    /**
     * Get Create
     * @param Request $request
     * @param UsersService $UsersService
     * @return view
     */
    public function create(Request $request, UsersService $UsersService)
    {
        $otherDistricts = $UsersService->sortDistricts(DivisionDistrict::getOptionsForSelect(), []);
        return view('dashboard.users.create', [
            'statuses'      => UserStatusType::all(),
            'securityGroup' => SecurityGroup::all(),
            'message'       => $this->message,
            'user'          => [],
            'otherDistricts' => $otherDistricts,
            'allDistricts'  => DivisionDistrict::getOptionsForSelect(),
        ]);
    }

    /**
     * Post Create
     * @param UpdateRequest $request
     * @param UsersService $UsersService
     * @return redirect
     */
    public function store(UpdateRequest $request, UsersService $UsersService)
    {
        $UsersService->createUser($request);
        return redirect()
            ->route('dashboard.users.index')
            ->with('notification.success', 'New User was successfully created.');
    }

    /**
     * Reset Password
     * @param $id
     * @return redirect
     */
    public function resetPasswordEmail($id)
    {
        $user = User::find($id);
        //TODO: Need to finish this
        //var_dump($TokenRepositoryInterface->tokens->create($user));
        //exit;
        //$user->sendPasswordResetNotification(app('auth.password.broker')->createToken($user));
        return redirect()
            ->route('dashboard.users.show', $id)
            ->with('notification.success', 'A password reset email has been sent to ' . $user->EmailAddress . '.');
    }

    /**
     * Delete User
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        $user = User::find($id);
        $user->setAttribute('UserStatus', 2);
        $user->save();

        return redirect()
            ->route('dashboard.users.index')
            ->with('notification.success', 'User has successfully been deleted.');
    }
}
