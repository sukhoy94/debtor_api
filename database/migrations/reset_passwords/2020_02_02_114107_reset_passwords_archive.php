<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ResetPasswordsArchive extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reset_passwords_arhive', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('user_id')->unsigned();
            $table->char('token');
            $table->timestamps();
            $table->timestamp('valid_until')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reset_passwords');
    }
}
