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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('client_id')->constrained('users');
            $table->foreignId('delivery_id')->nullable()->constrained();
            $table->enum('status', [
                'CREATED',
                'AWAITING_APPROVAL',
                'APPROVED',
                'DECLINED',
                'UNDER_DELIVERY',
                'FULFILLED',
                'CANCELED'
            ])->default('CREATED');
            $table->date('submitted_date')->nullable();
            $table->date('deadline_date');
            $table->text('decline_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
