<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name','code'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }
}
