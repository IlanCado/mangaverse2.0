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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->unsignedBigInteger('user_id');  // ID de l'utilisateur ayant écrit le commentaire
            $table->unsignedBigInteger('manga_id'); // ID du manga auquel le commentaire est associé
            $table->unsignedBigInteger('parent_id')->nullable(); // Réponse à un commentaire (optionnel)
            $table->timestamps();
    
            // Clés étrangères
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('manga_id')->references('id')->on('mangas')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('comments')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('comments');
    }
    
};
