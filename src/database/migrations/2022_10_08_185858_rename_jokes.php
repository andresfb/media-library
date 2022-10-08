<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('jokes', function (Blueprint $table) {
            $table->rename('cnt_jokes');
        });
    }

    public function down()
    {
        Schema::table('cnt_jokes', function (Blueprint $table) {
            $table->rename('jokes');
        });
    }
};
