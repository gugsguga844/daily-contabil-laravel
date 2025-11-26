<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $office = $request->user()->office;

        $users = $office->users()
            ->orderBy('name')
            ->paginate(10)
            ->through(fn ($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role_value' => $user->role->value,
                'role_label' => match ($user->role->value) {
                    'office_owner' => 'Proprietário',
                    'admin' => 'Administrador',
                    'worker' => 'Usuário',
                    default => Str::title(str_replace('_', ' ', $user->role->value)),
                },
            ]);

        $roles = collect(\App\Enums\UserRole::tenantRoles())
            ->map(fn ($v) => [
                'value' => $v,
                'label' => match ($v) {
                    'office_owner' => 'Proprietário',
                    'admin' => 'Administrador',
                    'worker' => 'Usuário',
                    default => Str::title(str_replace('_', ' ', $v)),
                },
            ])->values();

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // A criação é feita via modal na listagem
        return redirect()->route('manage.users.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:'.implode(',', UserRole::tenantRoles())],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // cast 'hashed' in model
            'office_id' => optional($user)->office_id,
            'role' => $request->role,
        ]);

        return redirect()->route('manage.users.index')
            ->with('success', 'Usuário criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
