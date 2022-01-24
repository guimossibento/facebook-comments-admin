<?php

use Illuminate\Database\Migrations\Migration as Migration;
use Illuminate\Database\Schema\Blueprint as Blueprint;
use Illuminate\Support\Facades\Schema as Schema;

class AlterFacebookAccountsTableAddSecret2faCollumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('facebook_accounts', function (Blueprint $table) {
            $table->string('secret_2fa')->nullable();
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
            $table->dropIfExists('secret_2fa');
        });
    }
}
