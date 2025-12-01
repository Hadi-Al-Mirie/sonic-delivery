<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\StoreUserRequest;
use App\Services\Admin\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request, UserService $userService)
    {
        $users = $userService->getUsers($request);
        return view('dashboard.users.index', compact('users'));
    }

    public function create(UserService $userService)
    {
        $roles = $userService->getRoles();
        return view('dashboard.users.add', compact('roles'));
    }

    public function store(StoreUserRequest $request, UserService $userService)
    {
        $userService->createUser($request);
        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function show($id, UserService $userService)
    {
        $user = $userService->getUserWithRole($id);
        return view('dashboard.users.show', compact('user'));
    }

    public function destroy($id, UserService $userService)
    {
        $userService->deleteUser($id);
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
