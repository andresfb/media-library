<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('quran', function (Blueprint $table) {
            $table->id();
            $table->string('hash', 64);
            $table->unsignedInteger('chapter_id');
            $table->string('chapter_name', 50);
            $table->unsignedInteger('verse_id');
            $table->text('verse');
            $table->unsignedSmallInteger("used")->default(0);

            $table->index(['hash', 'used'], 'hash_used_idx');
        });
    }
};
