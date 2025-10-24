<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $departments = Department::all();
        return view('profile.edit', compact('user', 'departments'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'department_id' => 'nullable|exists:departments,id',
            'position' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'signature' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // For file upload
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'department_id' => $request->department_id,
            'position' => $request->position,
            'phone' => $request->phone,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Handle signature upload
        if ($request->hasFile('signature')) {
            $uploadedFile = $request->file('signature');
            $path = $uploadedFile->store('signatures', 'public');
            $data['signature'] = '/storage/' . $path;
        } elseif ($request->filled('signature_data')) {
            // If it's base64 from canvas or typed
            $data['signature'] = $request->signature_data;
        }

        $user->update($data);

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }

    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }
}
