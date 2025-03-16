<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('career_opportunity_candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('career_opportunity_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('birthday')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('resume_path')->nullable();
            $table->enum('status', ['not-checked', 'rejected', 'accepted'])->default('not-checked');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('career_opportunity_candidates');
    }
};
