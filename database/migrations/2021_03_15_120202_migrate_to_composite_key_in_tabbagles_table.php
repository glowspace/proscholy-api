<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrateToCompositeKeyInTabbaglesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('taggables', function (Blueprint $table) {
            $table->dropIndex(['taggable_id']);
            $table->dropIndex(['taggable_type']);
        });

        Schema::table('taggables', function (Blueprint $table) {
            $table->index(['taggable_id', 'taggable_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('taggables', function (Blueprint $table) {
            $table->dropIndex(['taggable_id', 'taggable_type']);
        });

        Schema::table('taggables', function (Blueprint $table) {
            $table->index('taggable_id');
            $table->index('taggable_type');
        });
    }
}
