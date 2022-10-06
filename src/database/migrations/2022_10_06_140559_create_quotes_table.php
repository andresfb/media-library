<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->string('hash', 64);
            $table->text('quote');
            $table->string('author', 50);
            $table->string('category', 50);
            $table->unsignedSmallInteger("used")->default(0);

            $table->index(['hash', 'used'], 'hash_used_idx');
        });
    }

    public function down()
    {
        Schema::dropIfExists('quotes');
    }
};
