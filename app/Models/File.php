<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'original_name','name','path','mime_type',
        'category_id','department_id','uploaded_by',
        'is_signed','signed_by','signed_at','share_token','shared_until'
    ];

    protected $dates = ['shared_until','signed_at'];

    public function category(){ return $this->belongsTo(FileCategory::class); }
    public function department(){ return $this->belongsTo(Department::class); }
    public function uploader(){ return $this->belongsTo(User::class,'uploaded_by'); }
    public function signedBy(){ return $this->belongsTo(User::class,'signed_by'); }
    public function shares(){ return $this->hasMany(related: FileShare::class); }
    public function signature(){ return $this->hasOne(Signature::class); }

    // Access control: owner OR admin OR same-department OR shared email
    public function isAccessibleBy(User $user)
    {
        if(!$user) return false;
        if($user->hasRole('admin')) return true;
        if($this->uploaded_by === $user->id) return true;
        if($this->department_id && $user->department_id && $this->department_id === $user->department_id) return true;
        if($this->shares()->where('email', $user->email)->whereNotNull('id')->exists()) return true;
        return false;
    }

    public function isShareExpired()
    {
        return $this->shared_until && $this->shared_until->isPast();
    }

    // Helper methods for file display
    public function getSize()
    {
        $bytes = Storage::disk('public')->size($this->path);
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getExtension()
    {
        return pathinfo($this->original_name, PATHINFO_EXTENSION);
    }

    public function getIcon()
    {
        $extension = strtolower($this->getExtension());
        $iconMap = [
            'pdf' => 'file-pdf',
            'doc' => 'file-word',
            'docx' => 'file-word',
            'xls' => 'file-excel',
            'xlsx' => 'file-excel',
            'ppt' => 'file-powerpoint',
            'pptx' => 'file-powerpoint',
            'txt' => 'file-alt',
            'rtf' => 'file-alt',
            'jpg' => 'file-image',
            'jpeg' => 'file-image',
            'png' => 'file-image',
            'gif' => 'file-image',
            'bmp' => 'file-image',
            'svg' => 'file-image',
            'zip' => 'file-archive',
            'rar' => 'file-archive',
            '7z' => 'file-archive',
            'tar' => 'file-archive',
            'gz' => 'file-archive',
        ];

        return $iconMap[$extension] ?? 'file';
    }
}
