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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->string('booking_code')->unique(); // e.g. P1V120260729
            $table->string('customer_name');
            $table->text('customer_address');
            $table->string('customer_phone');
            $table->string('customer_sosmed')->nullable();
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->integer('total_nights')->default(1);
            $table->decimal('room_price', 12, 2);
            $table->decimal('discount', 5, 2)->nullable(); // percent % from room if any
            $table->decimal('total_price', 12, 2);
            $table->tinyInteger('status')->default(1); // 1 = Pending (2 Jam), 2 = Lunas, 0 = Batal/Expired
            $table->timestamp('expired_at')->nullable(); // Auto expiration time for pending
            $table->text('extra_facilities')->nullable(); // nullable
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
