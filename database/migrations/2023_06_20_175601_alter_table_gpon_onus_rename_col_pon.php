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
        Schema::table('gpon_onus', function (Blueprint $table) {
            $table->renameColumn('pon', 'port');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gpon_onus', function (Blueprint $table) {
            $table->renameColumn('port', 'pon');
        });
    }
};
