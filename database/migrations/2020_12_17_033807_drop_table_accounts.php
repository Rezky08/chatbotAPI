<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropTableAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('accounts');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('uid', 255);
            $table->integer('client_id');
            $table->text('account_detail')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
