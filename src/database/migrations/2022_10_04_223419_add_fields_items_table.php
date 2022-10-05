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
        Schema::table('items', function (Blueprint $table) {
            $table->boolean('active')
                ->default(true)
                ->after('type');

            $table->unsignedBigInteger('og_item_id')
                ->after('active')
                ->nullable();

            $table->dropIndex('type_idx');
            $table->index(['type', 'active'], 'type_active_idx');
            $table->index('og_item_id', 'og_item_id_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('items');

            $table->dropIndex('type_active_idx');

            $table->dropIndex('og_item_id_idx');

            $table->index('type', 'type_idx');
        });
    }
};
