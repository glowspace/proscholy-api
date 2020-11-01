<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiturgicalYearReadingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('liturgical_year_readings', function (Blueprint $table) {
            $table->id();

            $table->string('url');
            $table->date('date');
            $table->string('reference_type');
            $table->string('reference_1')->nullable();
            $table->string('reference_2')->nullable();
            $table->string('reference_3')->nullable();
            $table->string('references_others', 2000)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('liturgical_year_readings');
    }
}
