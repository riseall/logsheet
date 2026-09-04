<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormTemplate extends Model
{
    use HasFactory;

    protected $table = 'form_templates';

    protected $fillable = [
        'machine_id',
        'version',
        'created_by',
    ];

    public function mesin()
    {
        return $this->belongsTo(Mesin::class, 'machine_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function parameters()
    {
        return $this->hasMany(FormParameter::class, 'form_template_id')->orderBy('sort_order', 'asc');
    }

    public function logsheets()
    {
        return $this->hasMany(LogsheetHeader::class, 'form_template_id');
    }
}
