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
        Schema::create('gpon_ports', function (Blueprint $table) {
            $table->id();

            $table->string('port');
            $table->unsignedBigInteger('equipament_id');

            $table->foreign('equipament_id')->references('id')->on('gpon_equipaments');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gpon_ports');
    }
};
