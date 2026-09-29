<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vai_tro', function (Blueprint $table) {
            $table->integer('vai_tro_id')->autoIncrement()->primary();
            $table->string('ten_vai_tro', 50)->unique();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vai_tro');
    }
};