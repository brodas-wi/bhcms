<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Agregar campos a la tabla categories existente
        // Schema::table('categories', function (Blueprint $table) {
        //     $table->text('description')->nullable();
        //     $table->unsignedBigInteger('parent_id')->nullable();

        //     $table->foreign('parent_id')
        //         ->references('id')
        //         ->on('categories')
        //         ->onDelete('set null');
        // });

        // Crear tabla de contenidos
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content');
            $table->string('type'); // article, blog, news
            $table->string('status')->default('draft'); // draft, published
            $table->timestamp('published_at')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        // Crear tabla de etiquetas
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // Crear tabla pivote para contenidos y categorías
        Schema::create('content_category', function (Blueprint $table) {
            $table->foreignId('content_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->primary(['content_id', 'category_id']);
        });

        // Crear tabla pivote para contenidos y etiquetas
        Schema::create('content_tag', function (Blueprint $table) {
            $table->foreignId('content_id')->constrained()->onDelete('cascade');
            $table->foreignId('tag_id')->constrained()->onDelete('cascade');
            $table->primary(['content_id', 'tag_id']);
        });

        // Crear tabla para versiones de contenido
        Schema::create('content_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_id')->constrained()->onDelete('cascade');
            $table->text('content');
            $table->string('version_number');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        // Schema::table('categories', function (Blueprint $table) {
        //     $table->dropForeign(['parent_id']);
        //     $table->dropColumn(['description', 'parent_id']);
        // });

        Schema::dropIfExists('content_versions');
        Schema::dropIfExists('content_tag');
        Schema::dropIfExists('content_category');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('contents');
    }
};
