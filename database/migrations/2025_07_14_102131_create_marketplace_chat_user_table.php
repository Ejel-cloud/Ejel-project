<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('marketplace_chat_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketplace_chat_id')
                  ->constrained('marketplace_chats')
                  ->onDelete('cascade');
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->timestamps();

            $table->unique(['marketplace_chat_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('marketplace_chat_user');
    }
};
