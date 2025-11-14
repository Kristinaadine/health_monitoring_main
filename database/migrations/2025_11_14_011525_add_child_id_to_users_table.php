<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambah kolom child_id yang PERMANEN per user
            $table->string('child_id', 50)->nullable()->unique()->after('email');
        });
        
        // Generate child_id untuk user yang sudah ada
        $users = \App\Models\User::whereNull('child_id')->get();
        foreach ($users as $user) {
            $childId = '01-' . chr(65 + rand(0, 25)) . '-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $user->child_id = $childId;
            $user->save();
            
            \Log::info('Generated child_id for user', [
                'user_id' => $user->id,
                'child_id' => $childId
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('child_id');
        });
    }
};
