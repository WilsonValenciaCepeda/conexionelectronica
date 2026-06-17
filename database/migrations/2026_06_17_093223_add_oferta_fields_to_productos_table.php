<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->boolean('en_oferta')->default(false)->after('stock');
            $table->decimal('precio_oferta', 10, 2)->nullable()->after('en_oferta');
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn(['en_oferta', 'precio_oferta']);
        });
    }
};