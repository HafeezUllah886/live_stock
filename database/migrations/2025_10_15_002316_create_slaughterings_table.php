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
        Schema::create('slaughterings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('factory_id')->constrained('accounts')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('accounts')->cascadeOnDelete();
            $table->float('qty');
            $table->float('weight')->nullable();
            $table->float('price')->nullable();
            $table->float('amount')->nullable();
            $table->float('slaughtering_charges')->nullable();
            $table->float('slaughtering_amount')->nullable();
            $table->float('rejected_weight')->nullable();
            $table->float('rejected_price')->nullable();
            $table->float('rejected_amount')->nullable();
            $table->foreignId('butcher_id')->constrained('accounts')->cascadeOnDelete();
            $table->float('ober_price')->nullable();
            $table->float('ober_qty')->nullable();
            $table->float('ober_amount')->nullable();
            $table->foreignId('ober_id')->constrained('accounts')->cascadeOnDelete();
            $table->float('grand_total')->nullable();
            $table->date('date');
            $table->text('notes')->nullable();
            $table->bigInteger('refID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slaughterings');
    }
};
