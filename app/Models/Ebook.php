<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ebook extends Model
{
    use HasFactory;

    // DAFTARKAN SEMUA NAMA KOLOM DISINI AGAR BISA DI-ISI
    protected $fillable = [
        'title',
        'slug',
        'publisher',
        'publish_year',
        'category',
        'is_textbook',
        'related_course',
        'file_path',
        'cover_path',      // Pastikan nama kolom di database Anda 'cover_path'
        'download_count', // Pastikan nama kolom di database Anda 'download_count'
    ];
}
