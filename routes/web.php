<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\BienController;
use App\Http\Controllers\Web\ClientController;
use App\Http\Controllers\Web\VisiteController;
use App\Http\Controllers\Web\TransactionController;
use App\Http\Controllers\Web\NotificationController;
use App\Http\Controllers\Web\Client\EspaceClientController;
use Illuminate\Support\Facades\Route;

// ── Page d'accueil publique ────────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
});

// ── Alias Breeze (redirige vers le bon dashboard selon le rôle) ────────
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->isClient()) {
        return redirect()->route('client.dashboard');
    }
    return redirect()->route('web.dashboard');
})->middleware(['auth'])->name('dashboard');

// ── Espace Client ──────────────────────────────────────────────────────
Route::middleware(['auth', 'role:client'])->name('client.')->group(function () {
    Route::get('/mon-espace',            [EspaceClientController::class, 'dashboard'])->name('dashboard');
    Route::get('/mes-visites',           [EspaceClientController::class, 'mesVisites'])->name('mes-visites');
    Route::get('/mes-favoris',           [EspaceClientController::class, 'mesFavoris'])->name('mes-favoris');
    Route::get('/biens/{bien}/visite',   [EspaceClientController::class, 'demandeVisite'])->name('demande-visite');
    Route::post('/biens/{bien}/visite',  [EspaceClientController::class, 'soumettreVisite'])->name('soumettre-visite');
});

// ── Routes protégées admin/agent ───────────────────────────────────────
Route::middleware(['auth', 'role:admin,agent'])->name('web.')->group(function () {

    // Dashboard
    Route::get('/web/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Transactions
    Route::get('/transactions',      [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');

    // Clients
    Route::get('/clients',      [ClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/{id}', [ClientController::class, 'show'])->name('clients.show');

    // Visites
    Route::get('/visites',      [VisiteController::class, 'index'])->name('visites.index');
    Route::get('/visites/{id}', [VisiteController::class, 'show'])->name('visites.show');

    // Notifications
    Route::get('/notifications',             [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/compteur',    [NotificationController::class, 'compteur'])->name('notifications.compteur');
    Route::patch('/notifications/lire-tout', [NotificationController::class, 'marquerToutesLues'])->name('notifications.lire_tout');
    Route::patch('/notifications/{id}/lire', [NotificationController::class, 'marquerLue'])->name('notifications.lire');
});

// ── Biens (tous les rôles authentifiés) ───────────────────────────────
Route::middleware(['auth'])->name('web.')->group(function () {
    Route::get('/biens',        [BienController::class, 'index'])->name('biens.index');
    Route::get('/biens/{id}',   [BienController::class, 'show'])->name('biens.show');
});

// ── Profil Breeze ──────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';