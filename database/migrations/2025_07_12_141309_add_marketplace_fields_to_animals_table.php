<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('animals', function (Blueprint $table) {
            $table->boolean('for_sale')->default(false);
            $table->decimal('price', 10, 2)->nullable();
            $table->text('sale_description')->nullable();
            $table->timestamp('sale_started_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('animals', function (Blueprint $table) {
            //
        });
    }
};
