<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCommentLogsTableAddDebugJsonColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comment_logs', function (Blueprint $table) {
          $table->jsonb('debug_json')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('comment_logs', function (Blueprint $table) {
	    $table->dropIfExists('debug_json');
    });
    }
}
