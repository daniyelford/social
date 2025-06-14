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
        Schema::create('telegram_channels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->bigInteger('channel_id')->unique();
            $table->string('username')->nullable()->unique();;
            $table->string('title')->nullable();
            $table->integer('participants_count')->default(0);
            $table->text('about')->nullable();
            $table->text('photo_path')->nullable();
            $table->text('admins')->nullable();
            $table->decimal('price', 15, 2)->default(0);
            $table->float('influencer_score')->default(0);
            $table->integer('payment_type')->default(24);
            $table->boolean('insight')->default(false);
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_channels');
    }
};
