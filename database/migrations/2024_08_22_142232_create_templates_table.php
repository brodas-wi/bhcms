<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemplatesTable extends Migration
{
    public function up()
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->longText('styles');
            $table->unsignedBigInteger('user_id');
            $table->boolean('is_default')->default(false);
            $table->unsignedBigInteger('folder_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('thumbnail')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('folder_id')->references('id')->on('template_folders')->onDelete('set null');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');

            $table->index('name');
            $table->index('is_default');
        });

        Schema::create('template_has_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('template_id');
            $table->unsignedBigInteger('tag_id');
            $table->timestamps();

            $table->foreign('template_id')->references('id')->on('templates')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');

            $table->unique(['template_id', 'tag_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('template_has_tags');
        Schema::dropIfExists('templates');
    }
}
