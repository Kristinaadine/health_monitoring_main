<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\GrowthMonitoringModel;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Pastikan kolom child_id ada di tabel growth_monitoring
        if (!Schema::hasColumn('growth_monitoring', 'child_id')) {
            Schema::table('growth_monitoring', function (Blueprint $table) {
                $table->string('child_id', 50)->nullable()->after('users_id');
            });
        }
        
        // Update data lama yang belum punya child_id
        // Untuk setiap user, ambil data pertama dan generate child_id
        $users = GrowthMonitoringModel::select('users_id')
            ->whereNull('child_id')
            ->groupBy('users_id')
            ->get();
        
        foreach ($users as $user) {
            // Generate child_id untuk user ini
            $childId = '01-' . chr(65 + rand(0, 25)) . '-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            
            // Update semua record user ini dengan child_id yang sama
            GrowthMonitoringModel::where('users_id', $user->users_id)
                ->whereNull('child_id')
                ->update(['child_id' => $childId]);
            
            \Log::info('Migration - Updated child_id for user', [
                'user_id' => $user->users_id,
                'child_id' => $childId
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu rollback karena ini adalah data migration
    }
};
