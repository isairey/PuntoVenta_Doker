<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleIngresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_ingresos', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('idingreso')->unsigned();
            $table->integer('idarticulo')->unsigned();
            $table->integer('cantidad');
            $table->decimal('precio',11,2);
        });

        Schema::table('detalle_ingresos', function($table)
        {
            //$table->foreign('idingreso')->references('id')->on('ingresos')->constrained()->onDelete('cascade');;
            $table->foreign('idingreso')->references('id')->on('ingresos')->onDelete('cascade');;
            $table->foreign('idarticulo')->references('id')->on('articulos');   
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_ingresos');
    }
}
