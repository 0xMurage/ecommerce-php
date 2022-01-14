<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->bigIncrements('user_id');
            $table->bigIncrements('role_id');
            $table->timestamps();

            $table->foreign('user_id')->on('users')
                ->references('id')->onDelete('CASCADE');
            $table->foreign('role_id')->on('roles')
                ->references('id')->onDelete('CASCADE');

            $table->unique(['user_id', 'role_id']); #avoid unnecessary duplication
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_roles');
    }
}
