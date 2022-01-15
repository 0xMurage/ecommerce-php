<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->bigIncrements('role_id');
            $table->bigIncrements('permission_id');
            $table->timestamps();

            $table->foreign('role_id')->on('roles')
                ->references('id')->onDelete('CASCADE');

            $table->foreign('permission_id')->on('permissions')
                ->references('id')->onDelete('CASCADE');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_permissions');
    }
}
