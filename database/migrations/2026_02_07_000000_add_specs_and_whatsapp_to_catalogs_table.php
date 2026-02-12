<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('catalogs', function (Blueprint $table) {
            if (!Schema::hasColumn('catalogs', 'specification')) {
                $table->text('specification')->nullable()->after('desc');
            }
            if (!Schema::hasColumn('catalogs', 'whatsapp')) {
                $table->string('whatsapp')->nullable()->after('desc');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catalogs', function (Blueprint $table) {
            $table->dropColumn(['specification', 'whatsapp']);
        });
    }
};
