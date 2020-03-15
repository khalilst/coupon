<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCodeUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('code_user', function (Blueprint $table) {
            $table->unsignedBigInteger('code_id');
            $table->unsignedBigInteger('user_id');

            $table->timestamp('created_at');

            $table->foreign('code_id')
                ->references('id')->on('codes')
                ->onDelete('CASCADE');

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('code_user');
    }
}
