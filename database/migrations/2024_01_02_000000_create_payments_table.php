<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('order_id')->unique();
            $table->string('transaction_id')->nullable()->index();
            $table->unsignedBigInteger('amount');
            $table->string('status')->default('pending');     // pending|settlement|expire|cancel
            $table->string('payment_type')->default('qris');
            $table->string('issuer')->nullable();
            $table->string('reference')->nullable();           // mis. topup / order:123
            $table->text('qr_string')->nullable();
            $table->string('qr_url')->nullable();
            $table->string('checkout_url')->nullable();
            $table->json('meta')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
