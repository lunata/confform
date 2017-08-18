<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeLangFieldsInTableEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->renameColumn('PRIM_LANG', 'prim_lang');            
            $table->renameColumn('ADD_LANG', 'add_lang');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->renameColumn('prim_lang', 'PRIM_LANG');            
            $table->renameColumn('add_lang', 'ADD_LANG');            
        });
    }
}
