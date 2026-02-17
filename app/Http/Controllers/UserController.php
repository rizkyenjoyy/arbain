<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = User::with('roles');

        // Search by name
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by role
        if ($request->has('role') && !empty($request->role)) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Sort by specified column
        if ($request->has('sort') && in_array($request->sort, ['name', 'email', 'phone'])) {
            $query->orderBy($request->sort, $request->get('direction', 'asc'));
        }

        $activeUsers = User::where('last_activity', '>=', now()->subMinutes(5))->get();

        // Paginate the results
        $users = $query->paginate(10);  // Display 10 results per page
        $roles = Role::all();  // Get all roles for the filter dropdown

        return view('dashboard.users.index', compact('users', 'activeUsers', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        return view('dashboard.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15|unique:users',
            'nik' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        if ($validator->fails()) {
            return redirect()->route('users.create')
                             ->withErrors($validator)
                             ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'nik' => $request->nik,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('dashboard.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:15|unique:users,phone,' . $user->id,
            'nik' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        if ($validator->fails()) {
            return redirect()->route('users.edit', $user->id)
                             ->withErrors($validator)
                             ->withInput();
        }

        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'nik' => $request->nik,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        $user->syncRoles($request->role);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function updateLastActivity(Request $request)
    {
        $user = auth()->user();
        $user->last_activity = now(); // Update last activity time
        $user->save();

        return response()->json(['status' => 'success']);
    }

    public function deactivate($id)
    {
        $user = User::findOrFail($id);

        // Cegah user yang sedang login dinonaktifkan
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user->is_active = false;
        $user->save();

        return redirect()->route('users.index')->with('success', 'User berhasil dinonaktifkan.');
    }

    public function activate($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = true;
        $user->save();

        return redirect()->route('users.index')->with('success', 'User berhasil diaktifkan kembali.');
    }




}
