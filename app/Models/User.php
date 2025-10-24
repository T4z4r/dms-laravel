<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'department_id',
        'position',
        'phone',
        'status',
        'signature',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'status' => 'boolean',
    ];

    /**
     * Relations
     */

    // Each user belongs to one department
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Files uploaded by this user
    public function files()
    {
        return $this->hasMany(File::class, 'uploaded_by');
    }

    // Signatures by this user
    public function signatures()
    {
        return $this->hasMany(Signature::class, 'signed_by');
    }

    // Files shared to this user's email
    public function sharedFiles()
    {
        return $this->hasMany(FileShare::class, 'shared_to_email', 'email');
    }
}
