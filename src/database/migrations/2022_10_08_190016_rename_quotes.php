<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->rename('cnt_quotes');
        });
    }

    public function down()
    {
        Schema::table('cnt_quotes', function (Blueprint $table) {
            $table->rename('quotes');
        });
    }
};
