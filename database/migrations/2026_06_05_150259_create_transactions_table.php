<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\TypeTransaction;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('bien_id')->constrained('biens')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('agent_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', TypeTransaction::values());
            $table->decimal('montant', 15, 2);
            $table->decimal('commission_agence', 15, 2);
            $table->decimal('commission_agent', 15, 2)->nullable();
            $table->date('date_signature');
            $table->date('date_debut_contrat')->nullable();
            $table->date('date_fin_contrat')->nullable();
            $table->text('notes')->nullable();
            $table->string('contrat_path')->nullable(); // Chemin du PDF
            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index('reference');
            $table->index('type');
            $table->index('date_signature');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};