<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mesin extends Model
{
    use HasFactory;

    protected $table = 'machines';

    protected $fillable = [
        'name',
        'code',
        'type',
        'asset_number',
        'room',
        'category_id',
        'building_id',
        'status_aktif',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'category_id');
    }

    public function bangunan()
    {
        return $this->belongsTo(Bangunan::class, 'building_id');
    }

    public function templates()
    {
        return $this->hasMany(FormTemplate::class, 'machine_id');
    }

    public function latestTemplate()
    {
        return $this->hasOne(FormTemplate::class, 'machine_id')->latest('version');
    }

    public function logsheets()
    {
        return $this->hasMany(LogsheetHeader::class, 'machine_id');
    }
}
