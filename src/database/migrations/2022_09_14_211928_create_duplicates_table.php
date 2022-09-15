<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('duplicates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')
                ->constrained("items")
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('hash');
            $table->string('og_path');
            $table->string('og_file');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['hash', 'og_path']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('duplicates');
    }
};
