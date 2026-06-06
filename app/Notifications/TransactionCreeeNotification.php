<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TransactionCreeeNotification extends Notification
{
    use Queueable;

    public function __construct(public Transaction $transaction) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouvelle transaction - ' . $this->transaction->reference)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Une nouvelle transaction a ete enregistree :')
            ->line('**Reference :** ' . $this->transaction->reference)
            ->line('**Type :** ' . $this->transaction->type->label())
            ->line('**Bien :** ' . $this->transaction->bien->titre)
            ->line('**Client :** ' . $this->transaction->client->prenom . ' ' . $this->transaction->client->nom)
            ->line('**Montant :** ' . number_format($this->transaction->montant, 0, ',', ' ') . ' FCFA')
            ->line('**Commission agence :** ' . number_format($this->transaction->commission_agence, 0, ',', ' ') . ' FCFA')
            ->action('Voir la transaction', url('/api/v1/transactions/' . $this->transaction->id))
            ->line('Kaay Deuk Immobilier');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'               => 'transaction_creee',
            'titre'              => 'Nouvelle transaction enregistree',
            'message'            => $this->transaction->type->label()
                                 . ' - ' . $this->transaction->bien->titre
                                 . ' - ' . number_format($this->transaction->montant, 0, ',', ' ') . ' FCFA',
            'transaction_id'     => $this->transaction->id,
            'reference'          => $this->transaction->reference,
            'type_transaction'   => $this->transaction->type->value,
            'type_label'         => $this->transaction->type->label(),
            'montant'            => (float) $this->transaction->montant,
            'commission_agence'  => (float) $this->transaction->commission_agence,
            'bien_id'            => $this->transaction->bien_id,
            'bien_titre'         => $this->transaction->bien->titre,
            'client_nom'         => $this->transaction->client->prenom . ' ' . $this->transaction->client->nom,
        ];
    }
}