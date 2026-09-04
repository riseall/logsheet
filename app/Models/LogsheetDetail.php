<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogsheetDetail extends Model
{
    use HasFactory;

    protected $table = 'logsheet_details';

    protected $fillable = [
        'logsheet_header_id',
        'form_parameter_id',
        'value',
        'condition_status',
    ];

    public function header()
    {
        return $this->belongsTo(LogsheetHeader::class, 'logsheet_header_id');
    }

    public function parameter()
    {
        return $this->belongsTo(FormParameter::class, 'form_parameter_id');
    }
}
