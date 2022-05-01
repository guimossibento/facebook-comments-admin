<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateCommentLogsLoginTestColumn extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    DB::table('comment_logs')
      ->where('status', 'like', '%Login%')
      ->update(['login_test' => true]);

    DB::table('comment_logs')
      ->where('status', 'not like', '%Login%')
      ->update(['login_test' => false]);
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
  }
}
