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
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('member_card_id')->nullable()->constrained('member_cards')->nullOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('method', 30);
            $table->enum('status', ['paid', 'pending', 'failed', 'refunded'])->default('paid')->index();
            $table->string('reference', 50)->nullable()->index();
            $table->timestamp('paid_at');
            $table->timestamps();

            $table->index('paid_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
