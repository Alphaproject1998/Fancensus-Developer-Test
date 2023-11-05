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
        Schema::create('exchange_rates_bk', function (Blueprint $table) {
            $table->id();
            $table->string('countryName');
            $table->string('countryCode');
            $table->string('currencyName');
            $table->string('currencyCode');
            $table->float('rateNew');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_rates_bk');
    }
};
