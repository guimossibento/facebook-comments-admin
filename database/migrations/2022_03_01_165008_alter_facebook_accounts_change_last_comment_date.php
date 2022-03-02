<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFacebookAccountsChangeLastCommentDate extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('facebook_accounts', function (Blueprint $table) {
			$table->dateTime('last_comment')->default(\Carbon\Carbon::now()->subYears(5))->change();
		});
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('facebook_accounts', function (Blueprint $table) {
      $table->dateTime('last_comment')->nullable()->change();
		});
	}
}
