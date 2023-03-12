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
        Schema::create('table_house', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price_rub', 10, 2);
            $table->decimal('price_usd', 10, 2);
            $table->string('default_currency')->default('RUB');
            $table->integer('floors')->unsigned();
            $table->integer('bedrooms')->unsigned();
            $table->decimal('area', 8, 2); // 8 digits in total, with 2 decimal places
            $table->enum('object_type', ['House', 'Cottage', 'Townhouse', 'Apartment']);
            $table->string('image_gallery')->nullable();
            $table->unsignedBigInteger('village_id');
            $table
                ->foreign('village_id')
                ->references('id')
                ->on('table_village');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_house');
    }
};
