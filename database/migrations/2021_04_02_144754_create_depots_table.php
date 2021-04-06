<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('depots', function (Blueprint $table) {
            $table->string('phone_number')->primary();
            $table->string('location');
            $table->text('address');
            $table->integer('price')->default(5000);
            $table->boolean('is_open')->default(false);
            $table->timestamps();

            $table->foreign('phone_number')->references('phone_number')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('depots');
    }
}
