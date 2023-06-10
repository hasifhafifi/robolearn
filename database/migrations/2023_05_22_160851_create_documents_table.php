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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('docTitle');
            $table->text('docDesc');
            $table->string('docFilePath');
            $table->string('docType');
            $table->unsignedBigInteger('moduleID');
            $table->foreign('moduleID')->references('id')->on('modules')->onDelete('cascade');
            $table->boolean('isHidden')->default(false);
            $table->boolean('isDone')->default(false);
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
        Schema::dropIfExists('documents');
    }
};
