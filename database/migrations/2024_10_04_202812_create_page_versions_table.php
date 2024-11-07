<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageVersionsTable extends Migration
{
    public function up()
    {
        Schema::create('page_versions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('page_id');
            $table->text('content');
            $table->json('active_plugins')->nullable();
            $table->json('serialized_content')->nullable();
            $table->string('version');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('page_versions');
    }
}
