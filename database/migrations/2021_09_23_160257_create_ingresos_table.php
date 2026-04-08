<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingresos', function (Blueprint $table) {
            //$table->id();
            //$table->integer('id')->unsigned();
            $table->increments('id')->unsigned();
            //$table->increments('id')->unsigned();
            $table->integer('idproveedor')->unsigned();
            $table->integer('idusuario')->unsigned();
            $table->string('tipo_comprobante',20);
            $table->string('serie_comprobante',7)->nullable();
            $table->string('num_comprobante',10);
            $table->dateTime('fecha_hora');
            $table->decimal('impuesto',4,2);
            $table->decimal('total',11,2);
            $table->string('estado',20);
            $table->timestamps();
        });

        Schema::table('ingresos', function($table)
        {
            $table->foreign('idproveedor')->references('id')->on('proveedores');
            $table->foreign('idusuario')->references('id')->on('users');   
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ingresos');
    }
}
