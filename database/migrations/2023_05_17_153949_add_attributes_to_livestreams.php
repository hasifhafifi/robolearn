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
        Schema::create('livestreams', function (Blueprint $table) {
            $table->id();
            $table->string('streamName');
            $table->string('streamDesc')->nullable();
            $table->string('streamDate');
            $table->string('streamTime');
            $table->string('yt_streamID');
            $table->unsignedBigInteger('classroomID');
            $table->foreign('classroomID')->references('id')->on('classrooms')->onDelete('cascade');
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
        Schema::table('livestreams', function (Blueprint $table) {
            Schema::dropIfExists('modules');
        });
    }
};
