<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileShare extends Model
{
    protected $fillable = ['file_id', 'email', 'access', 'shared_by'];

    public function file()
    {
        return $this->belongsTo(File::class);
    }
    public function sharedBy()
    {
        return $this->belongsTo(User::class, 'shared_by');
    }
}
