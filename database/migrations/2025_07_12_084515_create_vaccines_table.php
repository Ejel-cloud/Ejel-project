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
        Schema::create('vaccines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_id')->constrained()->onDelete('cascade');
            $table->string('vaccine_type')->nullable();
            $table->string('name')->nullable();
            $table->date('date')->nullable();
            $table->text('notes')->nullable();
            $table->date('vaccination_date')->nullable();
            $table->date('next_vaccination_date')->nullable(); // هذا الحقل ناقص
            $table->string('veterinarian')->nullable(); // بعد إصلاح مكانه
            $table->string('image_path1')->nullable();
            $table->string('image_path2')->nullable();
            $table->string('image_path3')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vaccines');
    }
};
