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
        Schema::create('interfaces', function (Blueprint $table) {
            $table->id('cod_interface');

            $table->unsignedBigInteger('cod_host_fk');

            $table->string('name')->nullable(true);
            $table->string('address');
            $table->integer('port');

            $table->foreign('cod_host_fk')->references('cod_host')->on('hosts');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interfaces');
    }
};
