<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Office;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class OfficeUserController extends Controller
{
    public function edit(Office $office, User $user)
    {
        if ($user->office_id !== $office->id) {
            abort(404);
        }

        return Inertia::render('Admin/Offices/Users/Edit', [
            'office' => $office->only(['id', 'name']),
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role?->value,
            ],
            'roles' => collect(UserRole::tenantRoles())->map(fn ($v) => [
                'value' => $v,
                'label' => match ($v) {
                    'office_owner' => 'Proprietário',
                    'admin' => 'Administrador',
                    'worker' => 'Usuário',
                    default => $v,
                },
            ])->values(),
        ]);
    }

    public function update(Request $request, Office $office, User $user): RedirectResponse
    {
        if ($user->office_id !== $office->id) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(UserRole::tenantRoles())],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if (! empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        if ($office->office_owner_id === $user->id && $user->role !== UserRole::OFFICE_OWNER) {
            $office->office_owner_id = null;
            $office->save();
        }

        return redirect()->route('admin.offices.show', $office)->with('success', 'Usuário atualizado com sucesso.');
    }

    public function destroy(Office $office, User $user): RedirectResponse
    {
        if ($user->office_id !== $office->id) {
            abort(404);
        }

        if ($office->office_owner_id === $user->id) {
            $office->office_owner_id = null;
            $office->save();
        }

        $user->delete();

        return redirect()->route('admin.offices.show', $office)->with('success', 'Usuário excluído com sucesso.');
    }
}
