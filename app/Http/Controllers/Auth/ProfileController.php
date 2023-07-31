<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ProfileUpdateRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Class ProfileController
 * @package App\Http\Controllers\Auth
 */
class ProfileController extends Controller
{
    /**
     * Display user's profile
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view()
    {
        return view('auth.profile.view', [
            'user'  =>  Auth::user()
        ]);
    }

    /**
     * Handle update (POST) request
     *
     * @param ProfileUpdateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileUpdateRequest $request)
    {
        Auth::user()->update($request->data());

        return redirect()
            ->route('auth.profile.view')
            ->with('notification.success', 'Your profile was successfully updated.');
    }
}
