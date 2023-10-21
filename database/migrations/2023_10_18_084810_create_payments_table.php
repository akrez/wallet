<?php

use App\Enums\PaymentStatus;
use App\Enums\PaymentStatusEnum;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('unique_id')->nullable();
            $table->unsignedBigInteger('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
            $table->double('amount');
            $table->timestamps();
            $table->string('currency');
            $table->string('status')->default(PaymentStatusEnum::Pending);
            $table->timestamp('status_updated_at')->nullable();
            $table->foreignId('status_updated_by')->nullable()
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
