<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('bibles', function (Blueprint $table) {
            $table->id();
            $table->string("verse", 50);
            $table->text("kjb")->comment("King James Bible");
            $table->text("asv")->comment("American Standard Version");
            $table->text("drv")->comment("Douay-Rheims Bible");
            $table->text("dbt")->comment("Darby Bible Translation");
            $table->text("erv")->comment("English Revised Version");
            $table->text("wbt")->comment("Webster Bible Translation");
            $table->text("web")->comment("World English Bible");
            $table->text("ylt")->comment("Young's Literal Translation");
            $table->text("akj")->comment("American King James Version");
            $table->text("wnt")->comment("Weymouth New Testament");
            $table->boolean("used")->default(false);

            $table->index(['verse', 'used'], 'verse_used_idx');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bibles');
    }
};
