<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('footers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('content');
            $table->longText('css')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('template_id')->nullable()->constrained();
            $table->json('settings')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('footers');
    }
};
