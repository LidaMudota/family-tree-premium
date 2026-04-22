<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('people', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tree_id')->constrained()->cascadeOnDelete();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('birth_last_name')->nullable();
            $table->string('gender', 16)->default('unknown');
            $table->string('life_status', 16)->default('unknown');
            $table->string('birth_date_precision', 16)->default('unknown');
            $table->date('birth_date')->nullable();
            $table->unsignedSmallInteger('birth_year')->nullable();
            $table->unsignedTinyInteger('birth_month')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('death_date_precision', 16)->default('unknown');
            $table->date('death_date')->nullable();
            $table->unsignedSmallInteger('death_year')->nullable();
            $table->unsignedTinyInteger('death_month')->nullable();
            $table->string('death_place')->nullable();
            $table->string('summary_note', 240)->nullable();
            $table->text('full_note')->nullable();
            $table->string('photo_path')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->index(['tree_id', 'first_name', 'last_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
