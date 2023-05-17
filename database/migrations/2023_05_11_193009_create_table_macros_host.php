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
        Schema::create('macros_host', function (Blueprint $table) {
            $table->id('cod_macro_host');

            $table->unsignedBigInteger('cod_host_fk');

            $table->string('macro');
            $table->string('value');
            $table->string('type')->default('text');
            $table->string('description')->nullable(true);

            $table->foreign('cod_host_fk')->references('cod_host')->on('hosts');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('macros_host');
    }
};
