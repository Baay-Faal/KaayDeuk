<?php

namespace App\Policies;

use App\Models\Visite;
use App\Models\User;

class VisitePolicy
{
    /**
     * Determine if the user can view any visites.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isAgent();
    }

    /**
     * Determine if the user can view the visite.
     */
    public function view(User $user, Visite $visite): bool
    {
        // Admin peut voir toutes les visites
        if ($user->isAdmin()) {
            return true;
        }

        // Agent peut voir uniquement ses propres visites
        if ($user->isAgent()) {
            return $visite->agent_id === $user->id;
        }

        return false;
    }

    /**
     * Determine if the user can create visites.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isAgent();
    }

    /**
     * Determine if the user can update the visite.
     */
    public function update(User $user, Visite $visite): bool
    {
        // Admin peut modifier toutes les visites
        if ($user->isAdmin()) {
            return true;
        }

        // Agent peut modifier uniquement ses propres visites
        if ($user->isAgent()) {
            return $visite->agent_id === $user->id;
        }

        return false;
    }

    /**
     * Determine if the user can delete the visite.
     */
    public function delete(User $user, Visite $visite): bool
    {
        // Admin peut supprimer toutes les visites
        if ($user->isAdmin()) {
            return true;
        }

        // Agent peut supprimer uniquement ses propres visites non réalisées
        if ($user->isAgent()) {
            return $visite->agent_id === $user->id && !$visite->statut->value === 'realisee';
        }

        return false;
    }

    /**
     * Determine if the user can restore the visite.
     */
    public function restore(User $user, Visite $visite): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can permanently delete the visite.
     */
    public function forceDelete(User $user, Visite $visite): bool
    {
        return $user->isAdmin();
    }
}
