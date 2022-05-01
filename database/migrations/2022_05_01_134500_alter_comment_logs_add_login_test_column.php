<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCommentLogsAddLoginTestColumn extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('comment_logs', function (Blueprint $table) {
      $table->boolean('login_test')->default(false);
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
      $table->dropColumn('login_test');
    });
  }
}
