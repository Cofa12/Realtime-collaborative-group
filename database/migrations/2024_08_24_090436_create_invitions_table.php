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
        Schema::create('invitions', function (Blueprint $table) {
            $table->id();
            $table->string('message',255);
            $table->foreignId('group_id')->references('id')->on('groups');
            $table->tinyInteger('confirmation')->default(0);
            $table->foreignId('sender_id')->references('id')->on('users');
            $table->foreignId('receiver_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitions');
    }
};
