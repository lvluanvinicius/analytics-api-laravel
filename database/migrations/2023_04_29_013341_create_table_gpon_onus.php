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
        Schema::create('gpon_onus', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('serial_number');
            $table->string('device');
            $table->string('pon');
            $table->integer('onuid');
            $table->float('rx')->default(0.0);
            $table->float('tx')->default(0.0);

            $table->timestamp("collection_date");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gpon_onus');
    }
};
