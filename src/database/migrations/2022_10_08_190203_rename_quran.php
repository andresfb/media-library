<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('quran', function (Blueprint $table) {
            $table->rename('cnt_quran');
        });
    }

    public function down()
    {
        Schema::table('cnt_quran', function (Blueprint $table) {
            $table->rename('quran');
        });
    }
};
