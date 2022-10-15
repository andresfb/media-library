<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->boolean('has_exif')
                ->default(false)
                ->after('og_item_id');

            $table->json('exif')
                ->nullable()
                ->after('has_exif');

            $table->index(['has_exif', 'active'], 'exif_active_idx');
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropIndex('exif_active_idx');

            $table->dropColumn('has_exif');

            $table->dropColumn('exif');
        });
    }
};
