<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // This method is to get all users
    public function index()
    {
        $users = User::paginate(10);
        return view('admin.users.index', [
            'users' => $users
        ]);
    }

    // This method redirects to the create user page
    public function create()
    {
        $roles = Role::cases();
        return view('admin.users.create', [
            'roles' => $roles
        ]);
    }

    // This method is to store a user
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:CLIENT,WAREHOUSE_MANAGER,SYSTEM_ADMIN',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    // This method redirects to the show user page
    public function show(User $user)
    {
        return view('admin.users.show', [
            'user' => $user
        ]);
    }

    // This method redirects to the edit user page
    public function edit(User $user)
    {
        $roles = Role::cases();
        return view('admin.users.edit', [
            'user' => $user,
            'roles' => $roles
        ]);
    }

    // This method is to update a user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:CLIENT,WAREHOUSE_MANAGER,SYSTEM_ADMIN',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    // This method is to delete a user
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
