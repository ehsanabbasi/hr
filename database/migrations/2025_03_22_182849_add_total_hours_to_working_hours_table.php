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
        Schema::table('working_hours', function (Blueprint $table) {
            $table->float('total_hours')->nullable()->after('break_end_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('working_hours', function (Blueprint $table) {
            $table->dropColumn('total_hours');
        });
    }
};
