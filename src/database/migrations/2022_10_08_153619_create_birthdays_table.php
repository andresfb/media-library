<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('cnt_birthdays', function (Blueprint $table) {
            $table->id();
            $table->string('hash', 64)->default('');
            $table->date('birthday');
            $table->text('name');
            $table->text('description')->nullable();
            $table->boolean('used')->default(false);

            $table->index(['hash', 'used'], 'hash_used_idx');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cnt_birthdays');
    }
};
