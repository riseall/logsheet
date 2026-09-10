<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $connection = 'db_auth';
    protected $table = 'mst_anggota';
    protected $primaryKey = 'id_anggota';
    public $incrementing = true;
    protected $keyType = 'int';

    /**
     * Kolom-kolom yang dapat diisi.
     */
    protected $fillable = [
        'nama',
        'nik',
        'nip',
        'email',
        'level_pmmt',
        'password_hash',
        'password',
    ];

    /**
     * Kolom yang disembunyikan dalam serialisasi.
     */
    protected $hidden = [
        'password',
        'password_hash',
        'remember_token',
    ];

    /**
     * Otentikasi password menggunakan password_hash (Bcrypt) atau fallback password.
     */
    public function getAuthPassword()
    {
        return $this->password_hash ?? $this->password;
    }

    /**
     * Accessor 'name' agar tetap kompatibel dengan layout & view Logsheet ($user->name).
     */
    public function getNameAttribute()
    {
        return $this->attributes['nama'] ?? $this->attributes['name'] ?? ('User ' . ($this->nik ?? $this->id_anggota));
    }

    /**
     * Accessor 'id' agar tetap mengembalikan nilai id_anggota.
     */
    public function getIdAttribute()
    {
        return $this->attributes['id_anggota'] ?? null;
    }

    public const ROLE_ADMIN = 'Admin';
    public const ROLE_SUPERVISOR = 'Supervisor';
    public const ROLE_MANAGER = 'Manager';
    public const ROLE_TEKNISI = 'Teknisi';

    public const ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_SUPERVISOR,
        self::ROLE_MANAGER,
        self::ROLE_TEKNISI,
    ];

    /**
     * Accessor 'role' baku PMMT: Admin, Supervisor, Manager, Teknisi.
     * // ponytail: Kembalikan null jika user tidak memiliki role di PMMT/Logsheet.
     */
    public function getRoleAttribute()
    {
        $role = trim($this->attributes['level_pmmt'] ?? $this->attributes['role'] ?? '');
        if ($role === '') {
            return null;
        }

        if (strcasecmp($role, 'Superuser') === 0 || strcasecmp($role, self::ROLE_ADMIN) === 0) {
            return self::ROLE_ADMIN;
        }

        foreach (self::ROLES as $validRole) {
            if (strcasecmp($role, $validRole) === 0) {
                return $validRole;
            }
        }

        return null;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }
    public function isSupervisor(): bool
    {
        return $this->role === self::ROLE_SUPERVISOR;
    }
    public function isManager(): bool
    {
        return $this->role === self::ROLE_MANAGER;
    }
    public function isTeknisi(): bool
    {
        return $this->role === self::ROLE_TEKNISI;
    }

    public function hasRole(string ...$roles): bool
    {
        if (!$this->role) {
            return false;
        }
        return in_array(strtolower($this->role), array_map('strtolower', $roles));
    }

    /**
     * Scope query untuk filter role PMMT.
     */
    public function scopeRole($query, string ...$roles)
    {
        $roles = array_map('strtolower', $roles);
        return $query->where(function ($q) use ($roles) {
            foreach ($roles as $r) {
                if ($r === 'admin') {
                    $q->orWhereIn('level_pmmt', [self::ROLE_ADMIN, 'Superuser']);
                } else {
                    $q->orWhere('level_pmmt', ucfirst($r));
                }
            }
        });
    }

    /**
     * Relasi ke header logsheet (di database log_db).
     */
    public function logsheets()
    {
        return $this->hasMany(LogsheetHeader::class, 'teknisi_id', 'id_anggota');
    }

    /**
     * Relasi ke template form (di database log_db).
     */
    public function createdTemplates()
    {
        return $this->hasMany(FormTemplate::class, 'created_by', 'id_anggota');
    }

    /**
     * Relasi ke log approval (di database log_db).
     */
    public function approvalLogs()
    {
        return $this->hasMany(ApprovalLog::class, 'user_id', 'id_anggota');
    }
}
