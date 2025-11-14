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
        // Pastikan semua user punya child_id
        $users = \App\Models\User::whereNull('child_id')->orWhere('child_id', '')->get();
        foreach ($users as $user) {
            $childId = '01-' . chr(65 + rand(0, 25)) . '-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $user->child_id = $childId;
            $user->save();
            \Log::info('Migration - Set child_id for user', ['user_id' => $user->id, 'child_id' => $childId]);
        }
        
        // Ubah kolom child_id menjadi NOT NULL dan UNIQUE
        Schema::table('users', function (Blueprint $table) {
            $table->string('child_id', 50)->nullable(false)->unique()->change();
        });
        
        \Log::info('Migration - child_id is now NOT NULL and UNIQUE');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('child_id', 50)->nullable()->change();
            $table->dropUnique(['child_id']);
        });
    }
};
