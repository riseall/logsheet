<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogsheetHeader extends Model
{
    use HasFactory;

    protected $table = 'logsheet_headers';

    protected $fillable = [
        'machine_id',
        'form_template_id',
        'teknisi_id',
        'date',
        'shift',
        'status',
        'spv_id',
        'spv_approved_at',
        'spv_note',
        'manager_id',
        'manager_approved_at',
        'manager_note',
    ];

    protected $casts = [
        'date' => 'date',
        'spv_approved_at' => 'datetime',
        'manager_approved_at' => 'datetime',
    ];

    public function mesin()
    {
        return $this->belongsTo(Mesin::class, 'machine_id');
    }

    public function template()
    {
        return $this->belongsTo(FormTemplate::class, 'form_template_id');
    }

    public function teknisi()
    {
        return $this->belongsTo(User::class, 'teknisi_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'spv_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function details()
    {
        return $this->hasMany(LogsheetDetail::class, 'logsheet_header_id');
    }

    public function approvalLogs()
    {
        return $this->hasMany(ApprovalLog::class, 'logsheet_header_id');
    }
}
