<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bangunan extends Model
{
    use HasFactory;

    protected $table = 'buildings';

    protected $fillable = ['name', 'code', 'location'];

    public function mesins()
    {
        return $this->hasMany(Mesin::class, 'building_id');
    }
}
