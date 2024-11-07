<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('slug');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->longText('content');
            $table->longText('serialized_content')->nullable();
            $table->json('active_plugins')->nullable();
            $table->json('plugin_data')->nullable();
            $table->unsignedBigInteger('navbar_id')->nullable();
            $table->unsignedBigInteger('footer_id')->nullable();
            $table->unsignedBigInteger('template_id');
            $table->unsignedBigInteger('user_id');
            $table->string('status')->default('draft');
            $table->string('thumbnail')->nullable();
            $table->timestamp('date_published')->nullable();
            $table->string('version')->default('1.0.0');
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('template_id')->references('id')->on('templates')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('navbar_id')->references('id')->on('navbars')->onDelete('set null');
            $table->foreign('footer_id')->references('id')->on('footers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
