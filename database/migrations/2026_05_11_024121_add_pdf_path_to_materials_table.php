<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
    {
        Schema::table('materials', function (Blueprint $table) {
            // Nambahin kolom pdf_path yang boleh kosong (nullable)
            $table->string('pdf_path')->nullable()->after('code_visualization');
        });
    }

    public function down()
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropColumn('pdf_path');
        });
    }
};
