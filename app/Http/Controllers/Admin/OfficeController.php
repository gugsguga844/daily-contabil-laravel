<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOfficeRequest;
use App\Models\Office;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class OfficeController extends Controller
{
    public function index(Request $request)
    {
        $offices = Office::query()
            ->with(['office_owner:id,name,email'])
            ->withCount('users')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->through(function (Office $o) {
                return [
                    'id' => $o->id,
                    'name' => $o->name,
                    'fantasy_name' => $o->fantasy_name,
                    'cnpj' => $o->cnpj,
                    'email' => $o->email,
                    'phone' => $o->phone,
                    'city' => $o->city,
                    'state' => $o->state,
                    'is_active' => (bool) $o->is_active,
                    'current_plan' => $o->current_plan,
                    'users_count' => (int) $o->users_count,
                    'office_owner' => $o->office_owner ? [
                        'id' => $o->office_owner->id,
                        'name' => $o->office_owner->name,
                        'email' => $o->office_owner->email,
                    ] : null,
                    'created_at' => $o->created_at,
                ];
            });

        return Inertia::render('Admin/Offices/Index', [
            'offices' => $offices,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Offices/Create');
    }

    public function store(StoreOfficeRequest $request)
    {
        $validated = $request->validated();

        $officeData = $validated['office'];
        $userData = $validated['user'];

        $office = null;

        try {
            DB::transaction(function () use (&$office, $officeData, $userData) {
                // Create Office
                $office = Office::create([
                    'name' => $officeData['name'],
                    'fantasy_name' => $officeData['fantasy_name'] ?? null,
                    'cnpj' => $officeData['cnpj'],
                    'phone' => $officeData['phone'] ?? null,
                    'email' => $officeData['email'] ?? null,
                    'street' => $officeData['street'] ?? null,
                    'number' => $officeData['number'] ?? null,
                    'city' => $officeData['city'] ?? null,
                    'state' => $officeData['state'] ?? null,
                    'zip_code' => $officeData['zip_code'] ?? null,
                    'is_active' => $officeData['is_active'] ?? true,
                    'office_owner_id' => null,
                    'current_plan' => $officeData['current_plan'] ?? null,
                ]);

                // Create first user for Office (tenant owner by default)
                $role = $userData['role'] ?? UserRole::OFFICE_OWNER->value;

                $user = User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make($userData['password']),
                    'office_id' => $office->id,
                    'role' => $role,
                ]);

                // Optionally set office_owner_id to the created user
                $office->office_owner_id = $user->id;
                $office->save();
            });
        } catch (QueryException $e) {
            // If a unique constraint is hit (e.g. CNPJ/email), return a validation error to the form.
            // MySQL duplicate entry is SQLSTATE 23000 with driver error code 1062.
            if ((int) ($e->errorInfo[1] ?? 0) === 1062) {
                $message = $e->getMessage();

                $errors = [];
                if (str_contains($message, 'offices_cnpj_unique') || str_contains($message, 'offices.offices_cnpj_unique')) {
                    $errors['office.cnpj'] = 'Este CNPJ já está em uso por outro escritório.';
                }
                if (str_contains($message, 'offices_email_unique') || str_contains($message, 'offices.offices_email_unique')) {
                    $errors['office.email'] = 'Este email já está em uso por outro escritório.';
                }

                if (! empty($errors)) {
                    return back()->withErrors($errors)->withInput();
                }
            }

            throw $e;
        }

        return redirect()->route('admin.dashboard')->with('success', 'Escritório e usuário criados com sucesso.');
    }

    public function show(Office $office)
    {
        $office->load(['office_owner:id,name,email']);

        $users = $office->users()
            ->orderBy('name')
            ->get()
            ->map(function (User $u) {
                return [
                    'id' => $u->id,
                    'name' => $u->name,
                    'email' => $u->email,
                    'role' => $u->role?->value,
                ];
            });

        return Inertia::render('Admin/Offices/Show', [
            'office' => [
                'id' => $office->id,
                'name' => $office->name,
                'fantasy_name' => $office->fantasy_name,
                'cnpj' => $office->cnpj,
                'email' => $office->email,
                'phone' => $office->phone,
                'street' => $office->street,
                'number' => $office->number,
                'city' => $office->city,
                'state' => $office->state,
                'zip_code' => $office->zip_code,
                'is_active' => (bool) $office->is_active,
                'current_plan' => $office->current_plan,
                'office_owner' => $office->office_owner ? [
                    'id' => $office->office_owner->id,
                    'name' => $office->office_owner->name,
                    'email' => $office->office_owner->email,
                ] : null,
                'created_at' => $office->created_at,
            ],
            'users' => $users,
        ]);
    }

    public function edit(Office $office)
    {
        return Inertia::render('Admin/Offices/Edit', [
            'office' => $office->only([
                'id',
                'name',
                'fantasy_name',
                'cnpj',
                'phone',
                'email',
                'street',
                'number',
                'city',
                'state',
                'zip_code',
                'is_active',
                'current_plan',
            ]),
        ]);
    }

    public function update(Request $request, Office $office): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'fantasy_name' => ['nullable', 'string', 'max:255'],
            'cnpj' => ['required', 'string', 'max:32', Rule::unique('offices', 'cnpj')->ignore($office->id)],
            'phone' => ['nullable', 'string', 'max:32'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('offices', 'email')->ignore($office->id)],
            'street' => ['nullable', 'string', 'max:255'],
            'number' => ['nullable', 'string', 'max:32'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:32'],
            'zip_code' => ['nullable', 'string', 'max:32'],
            'is_active' => ['sometimes', 'boolean'],
            'current_plan' => ['nullable', 'string', 'max:64'],
        ]);

        $office->update($validated);

        return redirect()->route('admin.offices.show', $office)->with('success', 'Escritório atualizado com sucesso.');
    }

    public function destroy(Office $office): RedirectResponse
    {
        DB::transaction(function () use ($office) {
            $office->users()->delete();
            $office->delete();
        });

        return redirect()->route('admin.offices.index')->with('success', 'Escritório excluído com sucesso.');
    }
}
