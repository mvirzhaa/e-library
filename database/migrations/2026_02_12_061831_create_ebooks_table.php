<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_create_ebooks_table.php

public function up()
{
    Schema::create('ebooks', function (Blueprint $table) {
        $table->id();
        $table->string('title');              // Judul Buku
        $table->string('slug')->unique();     // URL ramah SEO (misal: buku-biologi-dasar)
        $table->string('publisher');          // Penerbit
        $table->year('publish_year');         // Tahun
        $table->string('category');           // Kategori Buku
        $table->boolean('is_textbook')->default(false); // Buku Kuliah (Y/T) - Disimpan sebagai 0 atau 1
        $table->string('related_course')->nullable();   // Mata Kuliah Terkait
        $table->string('cover_path')->nullable();       // Path Gambar Cover
        $table->string('file_path');                    // Path File PDF
        $table->unsignedBigInteger('download_count')->default(0); // Jumlah Download (Default 0)
        $table->timestamps(); // Created_at & Updated_at
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebooks');
    }
};
