<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToLoans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loans', function (Blueprint $table) {
            // Tipo de pago mensual, trimestral, semestral
            $table->string('type')->nullable()->default(null)->after('amount');
            // En cuantos pagos va pagar todo
            $table->integer('period')->default(1)->after('type');
            // 3 formas: default=inicio
            // inicio; para antes
            // fijo; para amortizacion fija
            // variable; para amortizacion variable
            $table->string('method')->nullable()->default('inicio')->after('period');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loans', function (Blueprint $table) {
            //
        });
    }
}
