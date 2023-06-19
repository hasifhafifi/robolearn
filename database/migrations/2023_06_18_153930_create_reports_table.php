<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('tournamentName');
            $table->double('totalMarks');
            $table->string('totalCheckpoints');
            $table->boolean('passObjective')->default(false);
            $table->string('comment');
            $table->unsignedBigInteger('classID');
            $table->unsignedBigInteger('userID')->nullable();
            $table->unsignedBigInteger('groupID')->nullable();
            $table->foreign('classID')->references('id')->on('classrooms')->onDelete('cascade');
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('groupID')->references('id')->on('groups')->onDelete('cascade');
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
        Schema::dropIfExists('reports');
    }
};
