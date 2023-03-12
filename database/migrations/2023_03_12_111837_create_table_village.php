<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('table_village', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->decimal('area', 8, 2); // 8 digits in total, with 2 decimal places
            $table->string('hotline');
            $table->string('youtube_video')->nullable();
            $table->string('photo')->nullable();
            $table->string('presentation_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_village');
    }
};
