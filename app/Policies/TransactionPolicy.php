<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;

class TransactionPolicy
{
    /**
     * Determine if the user can view any transactions.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isAgent();
    }

    /**
     * Determine if the user can view the transaction.
     */
    public function view(User $user, Transaction $transaction): bool
    {
        // Admin peut voir toutes les transactions
        if ($user->isAdmin()) {
            return true;
        }

        // Agent peut voir uniquement ses propres transactions
        if ($user->isAgent()) {
            return $transaction->agent_id === $user->id;
        }

        return false;
    }

    /**
     * Determine if the user can create transactions.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isAgent();
    }

    /**
     * Determine if the user can update the transaction.
     */
    public function update(User $user, Transaction $transaction): bool
    {
        // Admin peut modifier toutes les transactions
        if ($user->isAdmin()) {
            return true;
        }

        // Agent peut modifier uniquement ses propres transactions
        if ($user->isAgent()) {
            return $transaction->agent_id === $user->id;
        }

        return false;
    }

    /**
     * Determine if the user can delete the transaction.
     */
    public function delete(User $user, Transaction $transaction): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can restore the transaction.
     */
    public function restore(User $user, Transaction $transaction): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can permanently delete the transaction.
     */
    public function forceDelete(User $user, Transaction $transaction): bool
    {
        return $user->isAdmin();
    }
}
