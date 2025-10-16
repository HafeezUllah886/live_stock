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
        Schema::create('route_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transporter_id')->constrained('accounts');
            $table->date('date');
            $table->float('amount');
            $table->string('notes')->nullable();
            $table->string('refID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('route_expenses');
    }
};
