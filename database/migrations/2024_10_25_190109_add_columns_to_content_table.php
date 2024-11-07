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
        Schema::table('contents', function (Blueprint $table) {
            // Campos para imagen destacada y extracto
            $table->string('featured_image')->nullable()->after('published_at');
            $table->text('excerpt')->nullable()->after('featured_image');

            // Campos SEO
            $table->string('meta_title', 60)->nullable()->after('excerpt');
            $table->string('meta_description', 160)->nullable()->after('meta_title');

            // Índices para mejorar el rendimiento
            $table->index('status');
            $table->index('type');
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contents', function (Blueprint $table) {
            // Eliminar campos
            $table->dropColumn([
                'featured_image',
                'excerpt',
                'meta_title',
                'meta_description'
            ]);

            // Eliminar índices
            $table->dropIndex(['status']);
            $table->dropIndex(['type']);
            $table->dropIndex(['published_at']);
        });
    }
};
