<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;

class ClientPolicy
{
    /**
     * Determine if the user can view any clients.
     */
    public function viewAny(User $user): bool
    {
        // Admin et Agent peuvent voir les clients
        return $user->isAdmin() || $user->isAgent();
    }

    /**
     * Determine if the user can view the client.
     */
    public function view(User $user, Client $client): bool
    {
        // Admin peut voir tous les clients
        if ($user->isAdmin()) {
            return true;
        }

        // Agent peut voir uniquement ses propres clients
        if ($user->isAgent()) {
            return $client->agent_id === $user->id;
        }

        return false;
    }

    /**
     * Determine if the user can create clients.
     */
    public function create(User $user): bool
    {
        // Seuls les admins et agents peuvent créer des clients
        return $user->isAdmin() || $user->isAgent();
    }

    /**
     * Determine if the user can update the client.
     */
    public function update(User $user, Client $client): bool
    {
        // Admin peut tout modifier
        if ($user->isAdmin()) {
            return true;
        }

        // Agent peut modifier uniquement ses propres clients
        if ($user->isAgent()) {
            return $client->agent_id === $user->id;
        }

        return false;
    }

    /**
     * Determine if the user can delete the client.
     */
    public function delete(User $user, Client $client): bool
    {
        // Admin peut tout supprimer
        if ($user->isAdmin()) {
            return true;
        }

        // Agent peut supprimer uniquement ses propres clients
        if ($user->isAgent()) {
            return $client->agent_id === $user->id;
        }

        return false;
    }

    /**
     * Determine if the user can manage favoris.
     */
    public function manageFavoris(User $user, Client $client): bool
    {
        // Admin peut tout gérer
        if ($user->isAdmin()) {
            return true;
        }

        // Agent peut gérer les favoris de ses clients
        if ($user->isAgent()) {
            return $client->agent_id === $user->id;
        }

        return false;
    }
}