<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCommentLogsDropLogin extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('comment_logs', function (Blueprint $table) {
			$table->dropColumn('facebook_account_login');
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
			$table->string('facebook_account_login')->nullable();
		});
	}
}
