<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePluginsTable extends Migration
{
    public function up()
    {
        Schema::create('plugins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('original_name');
            $table->text('description');
            $table->string('version');
            $table->string('author');
            $table->string('main_class')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_global')->default(false);
            $table->json('views')->nullable();
            $table->json('selected_hooks')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('plugins');
    }
}
