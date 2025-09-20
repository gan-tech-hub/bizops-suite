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
        Schema::table('reservations', function (Blueprint $table) {
            if (Schema::hasColumn('reservations', 'customer') || Schema::hasColumn('reservations','staff') || Schema::hasColumn('reservations','color')) {
                $table->dropColumn(['customer', 'staff', 'color']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('staff')->nullable();
            $table->string('customer')->nullable();
            $table->string('color')->nullable();
        });
    }
};
