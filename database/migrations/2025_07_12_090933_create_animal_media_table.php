<?php

// database/migrations/xxxx_xx_xx_create_animal_media_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('animal_media', function (Blueprint $table) {
            $table->id();
            $table->string('file_path'); // ✅ الحقل الموحد
            $table->morphs('imageable'); // يشمل imageable_id و imageable_type
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('animal_media');
    }
};

