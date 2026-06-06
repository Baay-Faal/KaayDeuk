<?php

namespace App\Notifications;

use App\Models\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ClientAssigneNotification extends Notification
{
    use Queueable;

    public function __construct(public Client $client) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'       => 'client_assigne',
            'titre'      => 'Nouveau client assigne',
            'message'    => 'Le client ' . $this->client->prenom . ' ' . $this->client->nom
                          . ' vous a ete assigne.',
            'client_id'  => $this->client->id,
            'client_nom' => $this->client->prenom . ' ' . $this->client->nom,
            'telephone'  => $this->client->telephone,
            'email'      => $this->client->email,
            'budget'     => $this->client->budget_format,
        ];
    }
}