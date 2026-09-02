<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('member_card_id')->nullable()->constrained('member_cards')->nullOnDelete();
            $table->timestamp('check_in');
            $table->timestamp('check_out')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->timestamps();

            $table->index(['check_in', 'check_out']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
