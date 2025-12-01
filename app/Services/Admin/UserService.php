<?php

namespace App\Services\Admin;

use App\Http\Requests\Admin\User\StoreUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserService
{
    public function getUsers(Request $request)
    {
        $query = User::query();
        if ($request->has('phone')) {
            $query->where('phone', 'like', '%' . $request->phone . '%');
        }
        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function getRoles()
    {
        return Role::all();
    }

    public function createUser(StoreUserRequest $request): void
    {
        $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public') ?? null;
        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'location' => $request->location,
            'profile_picture' => $profilePicturePath,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id,
        ]);
    }

    public function getUserWithRole($id)
    {
        return User::with('role')->findOrFail($id);
    }

    public function deleteUser($id): void
    {
        $user = User::findOrFail($id);
        $user->delete();
    }
}
