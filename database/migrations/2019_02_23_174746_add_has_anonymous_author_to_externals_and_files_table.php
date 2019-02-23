<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHasAnonymousAuthorToExternalsAndFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('externals', function (Blueprint $table) {
            $table->boolean('has_anonymous_author')->default(false);
        });

        Schema::table('files', function (Blueprint $table) {
            $table->boolean('has_anonymous_author')->default(false);
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
            $table->dropColumn('has_anonymous_author');
        });

        Schema::table('files', function (Blueprint $table) {
            $table->dropColumn('has_anonymous_author');
        });
    }
}
