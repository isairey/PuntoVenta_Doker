<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            

            $table->string('usuario')->unique();
            $table->string('password');
            $table->boolean('condicion')->dafault(1);
            
            $table->integer('idrol')->unsigned();
            $table->rememberToken();
            
        });

        Schema::table('users', function($table)
        {
            
            $table->foreign('id')->references('id')->on('personas')->onDelete('cascade');
            $table->foreign('idrol')->references('id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
