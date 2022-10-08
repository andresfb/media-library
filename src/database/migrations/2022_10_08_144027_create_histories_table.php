<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('cnt_history', function (Blueprint $table) {
            $table->id();
            $table->string('hash', 64);
            $table->date('event_date');
            $table->text('event');
            $table->boolean('used')->default(false);

            $table->index(['hash', 'used'], 'hash_used_idx');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cnt_history');
    }
};
