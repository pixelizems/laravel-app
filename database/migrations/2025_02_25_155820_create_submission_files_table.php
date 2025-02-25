<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('submission_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained()->onDelete('cascade');
            $table->string('path');
            $table->string('original_filename');
            $table->string('mime_type')->nullable();
            $table->integer('size')->nullable(); // Size in bytes
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('submission_files');
    }
};
