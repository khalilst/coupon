<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('link')->nullable();
            $table->unsignedBigInteger('amount')->nullable();
            $table->unsignedBigInteger('brand_id');
            $table->unsignedTinyInteger('type');

            $table->timestamp('published_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();

            $table->foreign('brand_id')
                ->references('id')->on('brands')
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
        Schema::dropIfExists('coupons');
    }
}
