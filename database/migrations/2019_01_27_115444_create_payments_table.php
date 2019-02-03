<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('purse_from')->unsigned()->nullable();
            $table->integer('purse_to')->unsigned()->nullable();
            $table->decimal('amount_from', 19, 2)->nullable();
            $table->decimal('amount_to', 19, 2)->nullable();
            $table->decimal('amount_usd', 19, 2);

            $table->timestamps();

            $table->foreign('purse_from')
                ->references('id')
                ->on('purses')
                ->onDelete('cascade');

            $table->foreign('purse_to')
                ->references('id')
                ->on('purses')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
