<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->string('sender', 15);
            $table->string('to', 15);
            $table->bigInteger('transaction_id')->unsigned();
            $table->string('message');
            $table->timestamps();

            $table->foreign('sender')->references('phone_number')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('to')->references('phone_number')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('transaction_id')->references('id')->on('transactions')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chats');
    }
}
