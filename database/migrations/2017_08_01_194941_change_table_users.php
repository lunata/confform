<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTableUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('first_name', 'first_name_'.env('PRIM_LANG'));            
            $table->renameColumn('last_name', 'last_name_'.env('PRIM_LANG')); 
            
            $table->string('first_name_'.env('ADD_LANG'),191)->nullable();         
            $table->string('last_name_'.env('ADD_LANG'),191)->nullable();
            
            $table->string('middle_name_'.env('PRIM_LANG'),50)->nullable();
            $table->string('middle_name_'.env('ADD_LANG'),50)->nullable();
            
            $table->unsignedSmallInteger('country_id')->nullable();
            $table->unsignedInteger('city_id')->nullable();
            
            $table->string('degree_'.env('PRIM_LANG'),45)->nullable();
            $table->string('degree_'.env('ADD_LANG'),45)->nullable();
            
            $table->string('stitle_'.env('PRIM_LANG'),45)->nullable();
            $table->string('stitle_'.env('ADD_LANG'),45)->nullable();
            
            $table->string('affil_'.env('PRIM_LANG'),255)->nullable();
            $table->string('affil_'.env('ADD_LANG'),255)->nullable();
            
            $table->string('position_'.env('PRIM_LANG'),255)->nullable();
            $table->string('position_'.env('ADD_LANG'),255)->nullable();
            
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name_'.env('ADD_LANG'), 
                'middle_name_'.env('PRIM_LANG'), 'middle_name_'.env('ADD_LANG'),
                'last_name_'.env('ADD_LANG'), 'country_id', 'city_id', 
                'degree_'.env('PRIM_LANG'), 'degree_'.env('ADD_LANG'), 
                'stitle_'.env('PRIM_LANG'), 'stitle_'.env('ADD_LANG'), 
                'affil_'.env('PRIM_LANG'), 'affil_'.env('ADD_LANG'), 'deleted_at', 
                'position_'.env('PRIM_LANG'), 'position_'.env('ADD_LANG')]);
            $table->renameColumn('first_name_'.env('PRIM_LANG'), 'first_name');            
            $table->renameColumn('last_name_'.env('PRIM_LANG'), 'last_name');            
        });
    }
}
