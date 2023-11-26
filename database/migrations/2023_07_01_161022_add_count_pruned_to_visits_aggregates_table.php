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
        Schema::table('visit_aggregates', function (Blueprint $table) {
            $table->unsignedInteger('count_pruned')->default(0);
            $table->renameColumn('count_total', 'count_after_prune');
        });
    }

    /** 
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('visit_aggregates', function (Blueprint $table) {
            $table->dropColumn('count_pruned');
            $table->renameColumn('count_after_prune', 'count_total');
        });
    }
};
