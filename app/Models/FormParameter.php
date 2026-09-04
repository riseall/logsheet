<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormParameter extends Model
{
    use HasFactory;

    protected $table = 'form_parameters';

    protected $fillable = [
        'form_template_id',
        'name',
        'requirement',
        'sort_order',
    ];

    public function template()
    {
        return $this->belongsTo(FormTemplate::class, 'form_template_id');
    }

    public function logsheetDetails()
    {
        return $this->hasMany(LogsheetDetail::class, 'form_parameter_id');
    }
}
