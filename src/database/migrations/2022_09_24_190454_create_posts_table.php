<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')
                ->references('id')
                ->on('items');
            $table->string('type', 10);
            $table->string('slug', 200);
            $table->string("title", 100);
            $table->text('content');
            $table->text('og_file');
            $table->unsignedTinyInteger('status')
                ->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['type', 'slug'], 'type_slug_idx');
        });
    }

    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
