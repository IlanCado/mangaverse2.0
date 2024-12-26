<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('mangas', function (Blueprint $table) {
            $table->boolean('is_validated')->default(false); // Par défaut, non validé
            $table->string('image_path')->nullable(); // Chemin vers l'image
        });
    }
    
    public function down()
    {
        Schema::table('mangas', function (Blueprint $table) {
            $table->dropColumn(['is_validated', 'image_path']);
        });
    }
    
};
