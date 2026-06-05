<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\TypeBien;
use App\Enums\TypeTransaction;
use App\Enums\StatutBien;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('biens', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('titre');
            $table->text('description');
            $table->enum('type_bien', TypeBien::values());
            $table->enum('type_transaction', TypeTransaction::values());
            $table->decimal('prix', 15, 2);
            $table->decimal('surface', 10, 2);
            $table->integer('nombre_pieces')->nullable();
            $table->integer('nombre_chambres')->nullable();
            $table->integer('nombre_salles_bain')->nullable();
            $table->string('adresse');
            $table->string('quartier');
            $table->string('ville');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->enum('statut', StatutBien::values())->default(StatutBien::DISPONIBLE->value);
            $table->foreignId('agent_id')->constrained('users')->onDelete('cascade');
            $table->text('caracteristiques')->nullable(); // JSON pour features supplémentaires
            $table->integer('annee_construction')->nullable();
            $table->boolean('meuble')->default(false);
            $table->boolean('climatise')->default(false);
            $table->boolean('securise')->default(false);
            $table->integer('nombre_vues')->default(0);
            $table->timestamp('date_publication')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Index pour améliorer les performances
            $table->index('type_bien');
            $table->index('type_transaction');
            $table->index('statut');
            $table->index('ville');
            $table->index('prix');
            $table->index(['latitude', 'longitude']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biens');
    }
};