<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre',50)->unique();
            $table->string('descripcion',100)->nullable();
            $table->boolean('condicion')->default(1);

        });

        Schema::table('roles', function($table)
        {
            DB::table('roles')->insert(array('id'=>'1','nombre'=>'Administrador','descripcion'=>'Administradios del sistema'));
            DB::table('roles')->insert(array('id'=>'2','nombre'=>'Vendedor','descripcion'=>'Vendedor de area'));
            DB::table('roles')->insert(array('id'=>'3','nombre'=>'Almacenista','descripcion'=>'Almacenista de area'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
