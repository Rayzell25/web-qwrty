<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warranty_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('invoice_record_id')->nullable()->constrained('invoice_records')->nullOnDelete();
            $table->string('full_name');
            $table->string('email');
            $table->string('whatsapp');
            $table->string('invoice_number');
            $table->string('product_name');
            $table->text('complaint');
            $table->string('attachment')->nullable();
            $table->string('status')->default('pending');
            $table->text('admin_note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warranty_claims');
    }
};
