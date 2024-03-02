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
            $table->string('name');
            $table->string('phone_number');
            $table->integer('guests');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            //$table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->text('service_id');
            $table->date('booking_date');
            $table->foreignId('track_id')->constrained()->cascadeOnDelete();
            $table->string('note')->nullable();
            $table->boolean('status')->default(false);
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
