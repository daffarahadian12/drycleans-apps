<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->text('address');
            $table->boolean('is_member')->default(false);
            $table->date('member_since')->nullable();
            $table->integer('total_transactions')->default(0);
            $table->decimal('total_spent', 10, 2)->default(0);
            $table->integer('points')->default(0);
            $table->decimal('points_earned_rate', 5, 2)->default(1.00); // Points per 1000 spent
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
