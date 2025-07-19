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
        Schema::table('vaccines', function (Blueprint $table) {
            // تأكد من عدم وجود العمود مسبقاً لتجنب الخطأ
            if (!Schema::hasColumn('vaccines', 'next_vaccination_date')) {
                $table->date('next_vaccination_date')->nullable()->after('vaccination_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vaccines', function (Blueprint $table) {
            if (Schema::hasColumn('vaccines', 'next_vaccination_date')) {
                $table->dropColumn('next_vaccination_date');
            }
        });
    }
};
