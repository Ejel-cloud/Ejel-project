<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('marketplace_chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained('marketplace_listings')->onDelete('cascade');
            $table->enum('type', ['public', 'private'])->default('private');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('marketplace_chats');
    }
};