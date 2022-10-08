<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('bibles', function (Blueprint $table) {
            $table->rename('cnt_bibles');
        });
    }

    public function down()
    {
        Schema::table('cnt_bibles', function (Blueprint $table) {
            $table->rename('bibles');
        });
    }
};
