<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Isi nilai default konfigurasi sistem
        $defaults = [
            ['key' => 'threshold_level1',          'value' => '40',  'created_at' => now(), 'updated_at' => now()],
            ['key' => 'threshold_level3',          'value' => '70',  'created_at' => now(), 'updated_at' => now()],
            ['key' => 'quiz_passing_grade',        'value' => '60',  'created_at' => now(), 'updated_at' => now()],
            ['key' => 'session_timeout_hours',     'value' => '8',   'created_at' => now(), 'updated_at' => now()],
            ['key' => 'autosave_interval_seconds', 'value' => '30',  'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($defaults as $config) {
            DB::table('system_configs')->updateOrInsert(
                ['key' => $config['key']],
                $config
            );
        }
    }

    public function down(): void
    {
        DB::table('system_configs')->whereIn('key', [
            'threshold_level1',
            'threshold_level3',
            'quiz_passing_grade',
            'session_timeout_hours',
            'autosave_interval_seconds',
        ])->delete();
    }
};
