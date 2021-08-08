<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Pengaduan extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'kategoriPengaduan', 'deskripsi', 'gambar'];

    public function pengaduan() {
        return  $this-> belongsTo('App\Models\User');
    }
    
}
