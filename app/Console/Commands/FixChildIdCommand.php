<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\GrowthMonitoringModel;

class FixChildIdCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'growth:fix-child-id {--user-id= : Fix untuk user tertentu}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix child_id untuk memastikan setiap user hanya punya 1 ID permanen';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Memulai fix child_id...');
        
        $userId = $this->option('user-id');
        
        if ($userId) {
            // Fix untuk user tertentu
            $this->fixUserChildId($userId);
        } else {
            // Fix untuk semua user
            $this->fixAllUsersChildId();
        }
        
        $this->info('✅ Selesai!');
    }
    
    private function fixUserChildId($userId)
    {
        $this->info("Memperbaiki child_id untuk user ID: {$userId}");
        
        // Ambil semua data user
        $records = GrowthMonitoringModel::where('users_id', $userId)
            ->orderBy('created_at', 'asc')
            ->get();
        
        if ($records->isEmpty()) {
            $this->warn("Tidak ada data untuk user ID: {$userId}");
            return;
        }
        
        // Ambil child_id pertama (atau generate baru jika tidak ada)
        $firstChildId = $records->first()->child_id;
        
        if (empty($firstChildId)) {
            // Generate child_id baru
            $firstChildId = '01-' . chr(65 + rand(0, 25)) . '-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $this->info("Generate child_id baru: {$firstChildId}");
        } else {
            $this->info("Menggunakan child_id yang sudah ada: {$firstChildId}");
        }
        
        // Update semua record dengan child_id yang sama
        $updated = GrowthMonitoringModel::where('users_id', $userId)
            ->update(['child_id' => $firstChildId]);
        
        $this->info("✓ Updated {$updated} records dengan child_id: {$firstChildId}");
        
        // Verifikasi
        $uniqueIds = GrowthMonitoringModel::where('users_id', $userId)
            ->distinct('child_id')
            ->count('child_id');
        
        if ($uniqueIds == 1) {
            $this->info("✓ Verifikasi OK: User hanya punya 1 child_id");
        } else {
            $this->error("✗ Verifikasi GAGAL: User masih punya {$uniqueIds} child_id berbeda");
        }
    }
    
    private function fixAllUsersChildId()
    {
        $this->info("Memperbaiki child_id untuk semua user...");
        
        // Ambil semua user yang punya data
        $users = GrowthMonitoringModel::select('users_id')
            ->groupBy('users_id')
            ->get();
        
        $this->info("Total user: " . $users->count());
        
        $bar = $this->output->createProgressBar($users->count());
        $bar->start();
        
        foreach ($users as $user) {
            $this->fixUserChildId($user->users_id);
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        
        // Summary
        $this->info("\n📊 Summary:");
        $totalRecords = GrowthMonitoringModel::count();
        $recordsWithChildId = GrowthMonitoringModel::whereNotNull('child_id')->count();
        $recordsWithoutChildId = GrowthMonitoringModel::whereNull('child_id')->count();
        
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Records', $totalRecords],
                ['Records dengan child_id', $recordsWithChildId],
                ['Records tanpa child_id', $recordsWithoutChildId],
            ]
        );
        
        if ($recordsWithoutChildId > 0) {
            $this->warn("⚠️  Masih ada {$recordsWithoutChildId} records tanpa child_id!");
        } else {
            $this->info("✓ Semua records sudah punya child_id");
        }
    }
}
