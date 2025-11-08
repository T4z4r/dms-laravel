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

    public function share(User $user, File $file = null)
    {
        if ($file) {
            return $user->hasRole('admin') || $user->id === $file->uploaded_by;
        }
        // For class-level checks, allow if user is admin (can share any file)
        return $user->hasRole('admin');
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

    public function edit(User $user, File $file)
    {
        // allow uploader or admin OR shared with edit permission
        if($user->hasRole('admin')) return true;
        if($user->id === $file->uploaded_by) return true;
        // if explicit share with edit permission:
        return $file->shares()->where('email',$user->email)->whereIn('access',['edit','sign'])->exists();
    }

    public function comment(User $user, File $file)
    {
        // allow uploader, admin, or anyone with view access (since comment is less restrictive than edit)
        if($user->hasRole('admin')) return true;
        if($user->id === $file->uploaded_by) return true;
        // if explicit share with comment or higher permission:
        return $file->shares()->where('email',$user->email)->whereIn('access',['comment','edit','sign'])->exists();
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
