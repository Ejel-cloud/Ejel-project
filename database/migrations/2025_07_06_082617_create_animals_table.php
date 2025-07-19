<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('animals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');     // مالك الحيوان
            $table->string('type');                    // نوع الحيوان (غنم، بقر، إبل...)
            $table->enum('gender', ['female', 'male']);   // الجنس
            $table->date('birth_date')->nullable();    // تاريخ الميلاد
            $table->text('notes')->nullable();         // ملاحظات
            $table->string('image_path')->nullable();  // مسار الصورة
            $table->timestamps();

            // العلاقة مع جدول المستخدمين
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};
