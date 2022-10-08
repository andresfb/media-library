<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('wn_gloss', function (Blueprint $table) {
            $table->decimal('synset_id', 10, 0)->primary();
            $table->text('gloss');
        });
    }

    public function down()
    {
        Schema::dropIfExists('wn_gloss');
    }
};
