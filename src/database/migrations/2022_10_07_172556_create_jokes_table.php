<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('jokes', function (Blueprint $table) {
            $table->id();
            $table->string('hash', 64);
            $table->string('category', 80);
            $table->text('title');
            $table->text('body');
            $table->unsignedSmallInteger("used")->default(0);

            $table->index(['hash', 'used'], 'hash_used_idx');
        });
    }

    public function down()
    {
        Schema::dropIfExists('jokes');
    }
};
