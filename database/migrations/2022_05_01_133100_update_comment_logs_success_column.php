<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateCommentLogsSuccessColumn extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    DB::table('comment_logs')
      ->where('status', 'like', '%Sucesso%')
      ->update(['success' => true]);

    DB::table('comment_logs')
      ->where('status', 'not like', '%Sucesso%')
      ->update(['success' => false]);
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
