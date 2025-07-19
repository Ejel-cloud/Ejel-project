<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('marketplace_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('animal_id')->nullable()->constrained()->onDelete('set null');
            $table->string('title', 150)->nullable(); // ✅ لا تستعمل change() هنا
            $table->text('description')->nullable();
            $table->enum('category', ['animal', 'feed', 'equipment']);
            $table->string('animal_type')->nullable();
            $table->string('city');
            $table->decimal('price', 12, 2);
            $table->enum('status', ['active', 'sold', 'cancelled'])->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('marketplace_listings');
    }
};
