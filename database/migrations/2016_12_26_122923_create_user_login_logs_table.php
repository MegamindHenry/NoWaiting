<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLoginLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_login_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ip_addr', 20)->nullable();
            $table->string('device')->nullable();
            $table->integer('app_user_id');
            $table->timestamps('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_login_logs');
    }
}
