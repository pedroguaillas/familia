<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\Types\Nullable;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('person_id')->unsigned();
            $table->bigInteger('guarantor_id')->unsigned()->nullable();
            $table->float('interest_percentage', 5, 2);
            $table->decimal('amount', 8, 2);
            $table->dateTimeTz('date');
            $table->enum('state', ['activo', 'inactivo']);

            $table->foreign('person_id')->references('id')->on('people');
            $table->foreign('guarantor_id')->references('id')->on('people');

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
        Schema::dropIfExists('loans');
    }
}
