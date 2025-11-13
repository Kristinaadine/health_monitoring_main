<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ZScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate table first
        DB::table('zscore')->truncate();

        // Path to CSV file
        $csvFile = database_path('seeders/datazscore.csv');

        if (!File::exists($csvFile)) {
            $this->command->error('CSV file not found: ' . $csvFile);
            return;
        }

        $this->command->info('Reading Z-Score data from CSV...');

        // Read CSV file
        $file = fopen($csvFile, 'r');
        $count = 0;
        $batch = [];

        while (($row = fgetcsv($file)) !== false) {
            // Skip if row doesn't have enough columns
            if (count($row) < 12) {
                continue;
            }

            $batch[] = [
                'gender' => $row[1],      // L or P
                'type' => $row[2],        // LH or W
                'month' => $row[3],       // 0-60
                'L' => $row[4],           // L parameter
                'M' => $row[5],           // M parameter
                'S' => $row[6],           // S parameter
                'SD3neg' => $row[7],      // -3 SD
                'SD2neg' => $row[8],      // -2 SD
                'SD1neg' => $row[9],      // -1 SD
                'SD0' => $row[10],        // Median
                'SD1' => $row[11],        // +1 SD
                'SD2' => $row[12] ?? null, // +2 SD
                'SD3' => $row[13] ?? null, // +3 SD
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $count++;

            // Insert in batches of 100
            if (count($batch) >= 100) {
                DB::table('zscore')->insert($batch);
                $batch = [];
                $this->command->info("Inserted {$count} records...");
            }
        }

        // Insert remaining records
        if (count($batch) > 0) {
            DB::table('zscore')->insert($batch);
        }

        fclose($file);

        $this->command->info("✅ Successfully imported {$count} Z-Score records!");
        
        // Show summary
        $lhCount = DB::table('zscore')->where('type', 'LH')->count();
        $wCount = DB::table('zscore')->where('type', 'W')->count();
        $maleCount = DB::table('zscore')->where('gender', 'L')->count();
        $femaleCount = DB::table('zscore')->where('gender', 'P')->count();
        
        $this->command->info("Summary:");
        $this->command->info("- Height (LH): {$lhCount} records");
        $this->command->info("- Weight (W): {$wCount} records");
        $this->command->info("- Male (L): {$maleCount} records");
        $this->command->info("- Female (P): {$femaleCount} records");
    }
}
