<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('wn_synset', function (Blueprint $table) {
            $table->decimal('synset_id', 10, 0)->default(0);
            $table->decimal('w_num', 10, 0)->default(0);
            $table->string('word', 50)->nullable();
            $table->char('ss_type', 2)->nullable();
            $table->decimal('sense_number', 10, 0)->default(0);
            $table->decimal('tag_count', 10, 0)->nullable();

            $table->primary(['synset_id', 'w_num']);
            $table->index('synset_id');
            $table->index('w_num');
            $table->index('word');
        });
    }

    public function down()
    {
        Schema::dropIfExists('wn_synset');
    }
};
