<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\ProfileController;
use App\Http\Controllers\Api\V1\BienController;
use App\Http\Controllers\Api\V1\ClientController;
use App\Http\Controllers\Api\V1\VisiteController;
use App\Http\Controllers\Api\V1\TransactionController;
use App\Http\Controllers\Api\V1\FavoriController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\PdfController;
use App\Http\Controllers\Api\V1\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes – Kaay Deuk v1
|--------------------------------------------------------------------------
*/

// Route de test
Route::get('/test', function () {
    return response()->json([
        'success'   => true,
        'message'   => 'API Kaay_Deuk opérationnelle',
        'version'   => 'v1',
        'timestamp' => now()->toDateTimeString(),
    ]);
});

// ─── Routes publiques ──────────────────────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('/register', RegisterController::class)->name('auth.register');
    Route::post('/login', LoginController::class)->name('auth.login');
});

// ─── Routes protégées (Sanctum) ───────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // ── Authentification ───────────────────────────────────────────────────
    Route::prefix('auth')->group(function () {
        Route::post('/logout', LogoutController::class)->name('auth.logout');
        Route::get('/profile', [ProfileController::class, 'show'])->name('auth.profile');
        Route::put('/profile', [ProfileController::class, 'update'])->name('auth.profile.update');
        Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('auth.change-password');
    });

    // ── Notifications (tous les rôles) ─────────────────────────────────────
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/',            [NotificationController::class, 'index'])->name('index');
        Route::get('/compteur',    [NotificationController::class, 'compteur'])->name('compteur');
        Route::patch('/lire-tout', [NotificationController::class, 'marquerToutesLues'])->name('lire_tout');
        Route::delete('/lues',     [NotificationController::class, 'supprimerLues'])->name('supprimer_lues');
        Route::patch('/{id}/lire', [NotificationController::class, 'marquerLue'])->name('lire');
        Route::delete('/{id}',     [NotificationController::class, 'destroy'])->name('destroy');
    });

    // ── PDF (admin + agent uniquement) ─────────────────────────────────────
    Route::prefix('pdf')->name('pdf.')->middleware('role:admin,agent')->group(function () {
        Route::get('transactions/{transaction}/contrat', [PdfController::class, 'contrat'])->name('contrat');
        Route::get('transactions/{transaction}/recu',    [PdfController::class, 'recu'])->name('recu');
        Route::get('visites/{visite}/rapport',           [PdfController::class, 'rapportVisite'])->name('rapport_visite');
    });

    // ── Dashboard (admin + agent uniquement) ───────────────────────────────
    Route::prefix('dashboard')->name('dashboard.')->middleware('role:admin,agent')->group(function () {
        Route::get('/overview',     [DashboardController::class, 'overview'])->name('overview');
        Route::get('/transactions', [DashboardController::class, 'transactions'])->name('transactions');
        Route::get('/biens',        [DashboardController::class, 'biens'])->name('biens');
        Route::get('/visites',      [DashboardController::class, 'visites'])->name('visites');
        Route::get('/activite',     [DashboardController::class, 'activite'])->name('activite');
    });

    // ── Biens (tous les rôles authentifiés) ────────────────────────────────
    Route::apiResource('biens', BienController::class);
    Route::post('biens/{bien}/photos', [BienController::class, 'uploadPhotos'])
        ->name('biens.photos.upload');
    Route::delete('biens/{bien}/photos/{media}', [BienController::class, 'deletePhoto'])
        ->name('biens.photos.delete');

    // ── Clients (admin + agent uniquement) ────────────────────────────────
    Route::middleware('role:admin,agent')->group(function () {
        Route::apiResource('clients', ClientController::class);

        // Favoris
        Route::prefix('clients/{client}/favoris')->name('clients.favoris.')->group(function () {
            Route::get('/',       [FavoriController::class, 'index'])->name('index');
            Route::post('/',      [FavoriController::class, 'store'])->name('store');
            Route::delete('/{bien}', [FavoriController::class, 'destroy'])->name('destroy');
        });
    });

    // ── Visites (admin + agent uniquement) ────────────────────────────────
    Route::middleware('role:admin,agent')->group(function () {
        Route::get('visites/planifiees', [VisiteController::class, 'planifiees'])
            ->name('visites.planifiees');
        Route::patch('visites/{visite}/completer', [VisiteController::class, 'completer'])
            ->name('visites.completer');
        Route::apiResource('visites', VisiteController::class);
    });

    // ── Transactions (admin + agent uniquement) ────────────────────────────
    Route::apiResource('transactions', TransactionController::class)
        ->middleware('role:admin,agent');
});