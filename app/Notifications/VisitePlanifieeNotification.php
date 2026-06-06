<?php

namespace App\Notifications;

use App\Models\Visite;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class VisitePlanifieeNotification extends Notification
{
    use Queueable;

    public function __construct(public Visite $visite) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouvelle visite planifiee - ' . $this->visite->bien->titre)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Une nouvelle visite a ete planifiee pour le bien suivant :')
            ->line('**Bien :** ' . $this->visite->bien->titre)
            ->line('**Date :** ' . $this->visite->date_visite->format('d/m/Y'))
            ->line('**Heure :** ' . ($this->visite->heure_visite?->format('H:i') ?? 'N/A'))
            ->line('**Client :** ' . $this->visite->client->prenom . ' ' . $this->visite->client->nom)
            ->action('Voir la visite', url('/api/v1/visites/' . $this->visite->id))
            ->line('Kaay Deuk Immobilier');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'        => 'visite_planifiee',
            'titre'       => 'Nouvelle visite planifiee',
            'message'     => 'Visite planifiee le ' . $this->visite->date_visite->format('d/m/Y')
                           . ' pour ' . $this->visite->bien->titre,
            'visite_id'   => $this->visite->id,
            'bien_id'     => $this->visite->bien_id,
            'bien_titre'  => $this->visite->bien->titre,
            'client_nom'  => $this->visite->client->prenom . ' ' . $this->visite->client->nom,
            'date_visite' => $this->visite->date_visite->format('d/m/Y'),
            'heure_visite' => $this->visite->heure_visite?->format('H:i'),
        ];
    }
}