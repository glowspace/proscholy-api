<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeSomeVisitsIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->dropIndex(['visit_type']);

            $table->dropIndex(['visitable_id']);
            // diferent index name caused by column renaming
            $table->dropIndex('visits_visitable_index');
        });

        Schema::table('visits', function (Blueprint $table) {
            $table->index(['visitable_id', 'visitable_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->dropIndex(['visitable_id', 'visitable_type']);
        });

        Schema::table('visits', function (Blueprint $table) {
            $table->index('visit_type');
            $table->index('visitable_id');
            $table->index('visitable_type', 'visits_visitable_index');
        });
    }
}
