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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('moduleName');
            $table->string('moduleDesc')->nullable();
            $table->string('modulePic')->default('assets/img/slides-1.jpg');
            $table->boolean('isHidden')->default(false);
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
        Schema::dropIfExists('modules');
    }
};
