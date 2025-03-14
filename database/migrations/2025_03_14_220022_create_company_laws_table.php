<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_laws', function (Blueprint $table) {
            $table->id();
            $table->float('max_daily_hours')->default(10);
            $table->float('max_daily_break_hours')->default(1);
            $table->float('min_weekly_hours')->default(35);
            $table->float('max_weekly_hours')->default(48);
            $table->float('min_monthly_hours')->default(150);
            $table->float('max_monthly_hours')->default(208);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_laws');
    }
}; 