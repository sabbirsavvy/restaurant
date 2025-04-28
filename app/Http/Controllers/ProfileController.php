<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Auth;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone'          => ['nullable', 'string', 'max:20'],
            // New structured address fields:
            'address_line1'  => ['required', 'string', 'max:255'],
            'address_line2'  => ['nullable', 'string', 'max:255'],
            'town'           => ['required', 'string', 'max:100'],
            'county'         => ['required', 'string', 'max:100'],
            'postcode'       => ['required', 'string', 'max:20'],
            // Optional password update:
            'password'       => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Update basic fields:
        $user->name   = $request->input('name');
        $user->email  = $request->input('email');
        $user->phone  = $request->input('phone');

        // Update structured address:
        $user->address_line1 = $request->input('address_line1');
        $user->address_line2 = $request->input('address_line2');
        $user->town          = $request->input('town');
        $user->county        = $request->input('county');
        $user->postcode      = $request->input('postcode');

        // Update password if provided:
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function destroy(Request $request)
{
    $user = $request->user();
    
    // Delete user account
    $user->delete();

    // Logout user
    Auth::logout();

    // Redirect to home with message
    return redirect('/')->with('success', 'Your account has been deleted.');
}

}
