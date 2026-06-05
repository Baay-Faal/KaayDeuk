<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('prenom')->after('name');
            $table->string('telephone')->unique()->after('email');
            $table->string('adresse')->nullable()->after('telephone');
            $table->enum('role', Role::values())->default(Role::CLIENT->value)->after('adresse');
            $table->string('photo')->nullable()->after('role');
            $table->boolean('is_active')->default(true)->after('photo');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'prenom',
                'telephone',
                'adresse',
                'role',
                'photo',
                'is_active',
                'last_login_at'
            ]);
            $table->dropSoftDeletes();
        });
    }
};