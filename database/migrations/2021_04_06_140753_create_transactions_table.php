<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('client_phone_number', 15);
            $table->string('depot_phone_number', 15);
            $table->string('client_location');
            $table->smallInteger('status')->default(1)->comment('1 Menunggu persetujuan, 2 Mengambil galon, 3 Mengantar galon, 4 Selesai, 5 Dibatalkan');
            $table->integer('total_price');
            $table->integer('gallon');
            $table->integer('rating')->default(0);
            $table->timestamps();

            $table->foreign('client_phone_number')->references('phone_number')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('depot_phone_number')->references('phone_number')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
