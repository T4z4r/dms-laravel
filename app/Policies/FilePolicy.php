<?php
namespace App\Policies;

use App\Models\File;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilePolicy
{
    use HandlesAuthorization;

    public function view(User $user, File $file)
    {
        return $file->isAccessibleBy($user);
    }

    public function download(User $user, File $file)
    {
        return $file->isAccessibleBy($user);
    }

    public function delete(User $user, File $file)
    {
        return $user->hasRole('admin') || $user->id === $file->uploaded_by;
    }

    public function restore(User $user, File $file)
    {
        return $user->hasRole('admin');
    }

    public function forceDelete(User $user, File $file)
    {
        return $user->hasRole('admin');
    }

    public function share(User $user, File $file)
    {
        return $user->hasRole('admin') || $user->id === $file->uploaded_by;
    }

    public function sign(User $user, File $file)
    {
        // allow uploader, department head (role), or admin OR shared with sign permission
        if($user->hasRole('admin')) return true;
        if($user->id === $file->uploaded_by) return true;
        if($file->department_id && $user->department_id === $file->department_id && $user->hasRole('Department Head')) return true;
        // if explicit share with sign permission:
        return $file->shares()->where('email',$user->email)->where('access','sign')->exists();
    }

    public function viewSignature(User $user, File $file)
    {
        return $this->view($user,$file);
    }

    public function viewTrash(User $user)
    {
        return $user->hasRole('admin');
    }
}
