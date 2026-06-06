<?php

namespace App\Policies;

use App\Models\Bien;
use App\Models\User;
use App\Enums\Role;

class BienPolicy
{
    /**
     * Determine if the user can view any biens.
     */
    public function viewAny(User $user): bool
    {
        // Tous les utilisateurs authentifiés peuvent voir la liste
        return true;
    }

    /**
     * Determine if the user can view the bien.
     */
    public function view(User $user, Bien $bien): bool
    {
        // Tous peuvent voir un bien
        return true;
    }

    /**
     * Determine if the user can create biens.
     */
    public function create(User $user): bool
    {
        // Seuls les admins et agents peuvent créer
        return $user->isAdmin() || $user->isAgent();
    }

    /**
     * Determine if the user can update the bien.
     */
    public function update(User $user, Bien $bien): bool
    {
        // Admin peut tout modifier
        if ($user->isAdmin()) {
            return true;
        }

        // Agent peut modifier uniquement ses propres biens
        if ($user->isAgent()) {
            return $bien->agent_id === $user->id;
        }

        return false;
    }

    /**
     * Determine if the user can delete the bien.
     */
    public function delete(User $user, Bien $bien): bool
    {
        // Admin peut tout supprimer
        if ($user->isAdmin()) {
            return true;
        }

        // Agent peut supprimer uniquement ses propres biens
        if ($user->isAgent()) {
            return $bien->agent_id === $user->id;
        }

        return false;
    }

    /**
     * Determine if the user can restore the bien.
     */
    public function restore(User $user, Bien $bien): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can permanently delete the bien.
     */
    public function forceDelete(User $user, Bien $bien): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can upload photos.
     */
    public function uploadPhoto(User $user, Bien $bien): bool
    {
        // Admin peut uploader sur n'importe quel bien
        if ($user->isAdmin()) {
            return true;
        }

        // Agent peut uploader uniquement sur ses biens
        if ($user->isAgent()) {
            return $bien->agent_id === $user->id;
        }

        return false;
    }
}