<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('relationships', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tree_id')->constrained()->cascadeOnDelete();
            $table->foreignId('person_id')->constrained('people')->cascadeOnDelete();
            $table->foreignId('relative_id')->constrained('people')->cascadeOnDelete();
            $table->string('type', 24);
            $table->timestamps();
            $table->unique(['tree_id', 'person_id', 'relative_id', 'type'], 'uniq_relationship');
            $table->index(['tree_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('relationships');
    }
};
