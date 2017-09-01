<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('event_id')->nullable();
            $table->foreign('event_id')->references('id')->on('events');
            
            $table->unsignedInteger('parent_id')->nullable();
            $table->unsignedSmallInteger('prior')->nullable();
            $table->timestamps();
        });
        
        Schema::create('page_translations', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('page_id')->unsigned();
            $table->char('locale',2)->index();

            $table->string('title',150);
            $table->text('page');

            $table->unique(['page_id','locale']);
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
            // Adding a foreign key with cascading deletes will make sure that 
            // when a record in the pages-table gets removed, 
            // it’s corresponding translations will be removed as well.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('page_translations');
        Schema::dropIfExists('pages');
    }
}
