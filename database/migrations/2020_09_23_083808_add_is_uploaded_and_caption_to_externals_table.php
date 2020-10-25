<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsUploadedAndCaptionToExternalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('externals', function (Blueprint $table) {
            $table->boolean('is_uploaded')->default(false);
            $table->string('caption')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('externals', function (Blueprint $table) {
            $table->dropColumn('is_uploaded');
            $table->dropColumn('caption');
        });
    }
}
