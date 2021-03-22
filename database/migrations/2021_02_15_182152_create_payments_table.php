<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->bigIncrements('id');
            $table->bigInteger('loan_id')->unsigned();
            $table->decimal('debt', 8, 2);
            $table->decimal('interest_amount', 8, 2)->default(0);
            $table->decimal('capital', 8, 2)->default(0);
            $table->decimal('must', 8, 2)->default(0);
            $table->dateTimeTz('date');
            $table->enum('state', ['activo', 'inactivo']);
            $table->string('observation')->nullable();

            $table->foreign('loan_id')->references('id')->on('loans');

            $table->timestamps();
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
