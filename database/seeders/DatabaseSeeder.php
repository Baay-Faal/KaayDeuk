<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Bien;
use App\Models\Client;
use App\Models\Visite;
use App\Models\Transaction;
use App\Models\Favori;
use App\Enums\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Créer un administrateur
        $admin = User::factory()->create([
            'name' => 'Admin',
            'prenom' => 'Super',
            'email' => 'admin@kaaydeuk.com',
            'password' => bcrypt('password'),
            'role' => Role::ADMIN,
            'telephone' => '771234567',
        ]);

        echo "✅ Administrateur créé : admin@kaaydeuk.com / password\n";

        // Créer 5 agents
        $agents = User::factory(5)->agent()->create();
        echo "✅ 5 agents créés\n";

        // Créer 10 clients (2 par agent)
        $clients = collect();
        foreach ($agents as $agent) {
            $agentClients = Client::factory(2)->create([
                'agent_id' => $agent->id,
            ]);
            $clients = $clients->merge($agentClients);
        }
        echo "✅ 10 clients créés\n";

        // Créer 30 biens (6 par agent)
        $biens = collect();
        foreach ($agents as $agent) {
            $agentBiens = Bien::factory(6)->create([
                'agent_id' => $agent->id,
            ]);
            $biens = $biens->merge($agentBiens);
        }
        echo "✅ 30 biens créés\n";

        // Créer 20 visites
        foreach ($agents as $agent) {
            // Récupérer les biens et clients de cet agent
            $agentBiens = $biens->where('agent_id', $agent->id);
            $agentClients = $clients->where('agent_id', $agent->id);

            if ($agentBiens->isNotEmpty() && $agentClients->isNotEmpty()) {
                Visite::factory(4)->create([
                    'agent_id' => $agent->id,
                    'bien_id' => $agentBiens->random()->id,
                    'client_id' => $agentClients->random()->id,
                ]);
            }
        }
        echo "✅ 20 visites créées\n";

        // Créer 10 transactions
        foreach ($agents->take(2) as $agent) {
            $agentBiens = $biens->where('agent_id', $agent->id)->where('statut', 'disponible');
            $agentClients = $clients->where('agent_id', $agent->id);

            if ($agentBiens->isNotEmpty() && $agentClients->isNotEmpty()) {
                Transaction::factory(5)->create([
                    'agent_id' => $agent->id,
                    'bien_id' => $agentBiens->random()->id,
                    'client_id' => $agentClients->random()->id,
                ]);
            }
        }
        echo "✅ 10 transactions créées\n";

        // Créer des favoris (chaque client a 2-3 favoris)
        foreach ($clients as $client) {
            $biensDisponibles = $biens->where('statut', 'disponible')->random(rand(2, 3));
            
            foreach ($biensDisponibles as $bien) {
                Favori::create([
                    'client_id' => $client->id,
                    'bien_id' => $bien->id,
                ]);
            }
        }
        echo "✅ Favoris créés\n";

        echo "\n🎉 Base de données peuplée avec succès !\n";
        echo "📧 Admin: admin@kaaydeuk.com\n";
        echo "🔑 Password: password\n";
    }
}