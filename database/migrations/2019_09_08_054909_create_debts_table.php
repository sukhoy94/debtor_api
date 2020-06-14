<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \App\Models\Currency;

class CreateDebtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('amount');
            $table->integer('currency_id')->default(Currency::CURRENCIES['PLN']['id']);
            $table->bigInteger('lender_id')->unsigned();
            $table->bigInteger('debtor_id')->unsigned();
            $table->boolean('confirmed')->default(false);
            $table->boolean('payed')->default(false);
            $table->timestamps();

            $table->foreign('lender_id')->references('id')->on('users');
            $table->foreign('debtor_id')->references('id')->on('users');

            $table->index('lender_id');
            $table->index('debtor_id');
            $table->index('currency_id');
            $table->index('confirmed');
            $table->index('payed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('debts');
    }
}
