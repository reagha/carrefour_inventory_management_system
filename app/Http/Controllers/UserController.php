<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // 1. View all users (Read)
    public function index()
    {
        // Eager load the branch relationship to prevent N+1 queries
        $users = User::with('branch')->latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    // 2. Show the form to create a new user
    public function create()
    {
        $branches = Branch::all();
        return view('users.create', compact('branches'));
    }

    // 3. Save the new user (Create, Role Assignment, Branch Linking)
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:8|confirmed',
            'role'      => ['required', Rule::in(['admin', 'procurement', 'warehouse', 'branchManager', 'auditor'])],
            // Branch is only required if the role is 'branchManager'
            'branch_id' =>['nullable', 'exists:branches,id', Rule::requiredIf($request->role === 'branchManager')],
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password), // Secure hashing
            'role'      => $request->role,
            'branch_id' => $request->role === 'branchManager' ? $request->branch_id : null,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    // 4. Show the form to edit an existing user
    public function edit(User $user)
    {
        $branches = Branch::all();
        return view('users.edit', compact('user', 'branches'));
    }

    // 5. Save the updated user (Update)
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            // Ignore the current user's email so it doesn't throw a "unique" error on themselves
            'email'     => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password'  => 'nullable|string|min:8|confirmed', // Password is now optional!
            'role'      =>['required', \Illuminate\Validation\Rule::in(['admin', 'procurement', 'warehouse', 'branchManager', 'auditor'])],
            'branch_id' =>['nullable', 'exists:branches,id', \Illuminate\Validation\Rule::requiredIf($request->role === 'branchManager')],
        ]);

        // Prepare data
        $data =[
            'name'      => $request->name,
            'email'     => $request->email,
            'role'      => $request->role,
            'branch_id' => $request->role === 'branchManager' ? $request->branch_id : null,
        ];

        // Only hash and update the password if the admin actually typed a new one
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    // 6. Delete the user (Destroy)
    public function destroy(User $user)
    {
        // Safety check: Don't let the admin delete their own account!
        if (auth()->id() === $user->id) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
