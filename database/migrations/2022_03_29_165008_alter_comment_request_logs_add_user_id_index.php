<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCommentRequestLogsAddUserIdIndex extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('comment_request_logs', function (Blueprint $table) {
			$table->integer('user_id')->index()->nullable()->change();
		});
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('comment_request_logs', function (Blueprint $table) {
      $table->integer('user_id')->nullable(false)->change();
		});
	}
}
