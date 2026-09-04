<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = ['name', 'code'];

    public function mesins()
    {
        return $this->hasMany(Mesin::class, 'category_id');
    }
}
