<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->boolean('used')
                ->after('status')
                ->default(false);

            $table->index(['status', 'used'], 'status_used_idx');
        });
    }

    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex('status_used_idx');

            $table->dropColumn('used');
        });
    }
};
