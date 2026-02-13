<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            // Tambah kolom is_active, default 1 (Aktif)
            $table->boolean('is_active')->default(true)->after('name');
        });

        Schema::table('courses', function (Blueprint $table) {
            // Tambah kolom is_active, default 1 (Aktif)
            $table->boolean('is_active')->default(true)->after('name');
        });
    }

    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
