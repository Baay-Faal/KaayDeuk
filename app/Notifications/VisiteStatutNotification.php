<?php

namespace App\Notifications;

use App\Models\Visite;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class VisiteStatutNotification extends Notification
{
    use Queueable;

    public function __construct(public Visite $visite) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $statut = $this->visite->statut->value;

        $titre = match($statut) {
            'realisee' => 'Visite realisee',
            'annulee'  => 'Visite annulee',
            default    => 'Statut de visite mis a jour',
        };

        $message = match($statut) {
            'realisee' => 'La visite du ' . $this->visite->date_visite->format('d/m/Y')
                        . ' pour ' . $this->visite->bien->titre . ' a ete realisee.',
            'annulee'  => 'La visite du ' . $this->visite->date_visite->format('d/m/Y')
                        . ' pour ' . $this->visite->bien->titre . ' a ete annulee.',
            default    => 'Le statut de la visite a ete mis a jour : ' . $this->visite->statut->label(),
        };

        return [
            'type'        => 'visite_statut',
            'titre'       => $titre,
            'message'     => $message,
            'visite_id'   => $this->visite->id,
            'bien_id'     => $this->visite->bien_id,
            'bien_titre'  => $this->visite->bien->titre,
            'statut'      => $statut,
            'statut_label' => $this->visite->statut->label(),
            'date_visite' => $this->visite->date_visite->format('d/m/Y'),
            'note_client' => $this->visite->note_client,
        ];
    }
}