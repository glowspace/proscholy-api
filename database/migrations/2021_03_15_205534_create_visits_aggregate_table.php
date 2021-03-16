<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitsAggregateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visit_aggregates', function (Blueprint $table) {
            // $table->id();
            $table->string('visitable_type');
            $table->unsignedBigInteger('visitable_id');

            $table->unsignedInteger('count_week');
            $table->unsignedInteger('count_total');
        });

        Schema::table('visit_aggregates', function (Blueprint $table) {
            $table->primary(['visitable_type', 'visitable_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visit_aggregates');
    }
}
