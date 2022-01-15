<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_products', function (Blueprint $table) {
            https://laravel.com/docs/8.x/migrations#column-method-foreignId            $table->id();
            $table->string('name')->nullable(false);
            $table->text('description');
            $table->string('type');
            $table->string('category'); //we could refactor to its individual table?
            $table->string('manufacturer'); // also this should be a table
            $table->string('distributor');
            $table->double('quantity')->nullable(false);
            $table->double('unit_cost')->nullable(false);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');; //owner

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
        Schema::dropIfExists('user_products');
    }
}
