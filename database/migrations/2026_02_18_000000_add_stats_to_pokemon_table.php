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
        Schema::table('pokemon', function (Blueprint $table) {
            $table->integer('hp')->nullable()->after('type2');
            $table->integer('attack')->nullable()->after('hp');
            $table->integer('defense')->nullable()->after('attack');
            $table->integer('sp_attack')->nullable()->after('defense');
            $table->integer('sp_defense')->nullable()->after('sp_attack');
            $table->integer('speed')->nullable()->after('sp_defense');
            $table->decimal('weight_kg', 5, 2)->nullable()->after('speed');
            $table->decimal('height_m', 5, 2)->nullable()->after('weight_kg');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pokemon', function (Blueprint $table) {
            $table->dropColumn(['hp', 'attack', 'defense', 'sp_attack', 'sp_defense', 'speed', 'weight_kg']);
        });
    }
};
