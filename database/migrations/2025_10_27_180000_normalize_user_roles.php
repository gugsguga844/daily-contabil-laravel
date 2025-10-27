<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Normalize legacy role values
        DB::table('users')->where('role', 'office-owner')->update(['role' => 'office_owner']);
        DB::table('users')->where('role', 'implantacao')->update(['role' => 'implantation']);
        DB::table('users')->where('role', 'super-admin')->update(['role' => 'super_admin']);
        DB::table('users')->where('role', 'user')->update(['role' => 'worker']);

        // System users do not have an office_id
        DB::table('users')->whereIn('role', ['super_admin', 'implantation'])->update(['office_id' => null]);
    }

    public function down(): void
    {
        // Revert to previous values where possible
        DB::table('users')->where('role', 'office_owner')->update(['role' => 'office-owner']);
        DB::table('users')->where('role', 'implantation')->update(['role' => 'implantacao']);
        DB::table('users')->where('role', 'super_admin')->update(['role' => 'super-admin']);
        DB::table('users')->where('role', 'worker')->update(['role' => 'user']);
    }
};
