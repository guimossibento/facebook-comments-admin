<?php

namespace App\Interface\Http\Controllers\Auth;

use App\Interface\Http\Controllers\Controller;
use App\Interface\Http\Requests\Users\ChangePasswordRequest;
use App\Interface\Http\Requests\Users\ProfileUpdateRequest;
use  App\Domain\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Return the user data
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        $response = [
            'success' => true,
            'data'    => auth('api')->user(),
            'message' => 'User Profile',
        ];
        return response()->json($response, 200);
    }


    /**
     * Update the profile by users
     *
     * @param  \App\Interface\Http\Requests\Users\ProfileUpdateRequest  $request
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateProfile(ProfileUpdateRequest $request)
    {
        $user = auth('api')->user();

        $user->update($request->all());

        $response = [
            'success' => true,
            'data'    => $user,
            'message' => 'Profile has been updated',
        ];
        return response()->json($response, 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Interface\Http\Requests\Users\ChangePasswordRequest  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        User::find(auth('api')->user()->id)->update(['password' => Hash::make($request->new_password)]);

        $response = [
            'success' => true,
            'data'    => [],
            'message' => 'Password Has been updated',
        ];
        return response()->json($response, 200);
    }
}
