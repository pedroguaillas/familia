<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStateToSpends extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('spends', function (Blueprint $table) {
            // Se agrega el campo state para considerar si afecta o no al monto de las acciones
            $table->string('state')->nullable()->default(null)->after('observation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('spends', function (Blueprint $table) {
            //
        });
    }
}
