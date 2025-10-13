<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOfficeRequest;
use App\Models\Office;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Database\Seeders\CategorySeeder;

class OfficeController extends Controller
{
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

            (new CategorySeeder())->run($office->id);

            // Create first user for Office (tenant owner by default)
            $role = $userData['role'] ?? 'office-owner';

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

        return redirect()->route('admin.dashboard')->with('success', 'Escritório e usuário criados com sucesso.');
    }
}
