<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conferences', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title_'.env('PRIM_LANG'),150)->nullable();
            $table->string('title_'.env('ADD_LANG'),150)->nullable();

            $table->char('PRIM_LANG',2)->default(env('PRIM_LANG'));
            $table->char('ADD_LANG',2)->default(env('ADD_LANG'));
            
            $table->date('started_at')->nullable();
            $table->date('finished_at')->nullable();
            
            $table->unsignedInteger('city_id')->nullable();
            $table->foreign('city_id')->references('id')->on('cities');
            
            $table->date('registr_start')->nullable();
            $table->date('registr_finish')->nullable();
            
            $table->date('material_start')->nullable();
            $table->date('material_finish')->nullable();
            
            $table->unsignedTinyInteger('status')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conferences');
    }
}
