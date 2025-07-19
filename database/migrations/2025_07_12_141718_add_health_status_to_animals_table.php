<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHealthStatusToAnimalsTable extends Migration
{
    public function up()
    {
        Schema::table('animals', function (Blueprint $table) {
            $table->string('health_status')->default('healthy')->after('image_path');
        });
    }

    public function down()
    {
        Schema::table('animals', function (Blueprint $table) {
            $table->dropColumn('health_status');
        });
    }
}