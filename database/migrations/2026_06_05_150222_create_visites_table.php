<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\StatutVisite;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('visites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bien_id')->constrained('biens')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('agent_id')->constrained('users')->onDelete('cascade');
            $table->date('date_visite');
            $table->time('heure_visite');
            $table->enum('statut', StatutVisite::values())->default(StatutVisite::PLANIFIEE->value);
            $table->text('notes')->nullable();
            $table->text('rapport')->nullable();
            $table->integer('note_client')->nullable(); // Note de 1 à 5
            $table->text('commentaire_client')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index('date_visite');
            $table->index('statut');
            $table->index(['bien_id', 'client_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visites');
    }
};