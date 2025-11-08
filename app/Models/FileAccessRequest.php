<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileAccessRequest extends Model
{
    protected $fillable = [
        'file_id', 'requester_id', 'approver_id', 'requested_access',
        'status', 'request_message', 'response_message', 'responded_at'
    ];

    protected $dates = ['responded_at'];

    public function file()
    {
        return $this->belongsTo(File::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function approve(User $approver, $message = null)
    {
        $this->update([
            'status' => 'approved',
            'approver_id' => $approver->id,
            'response_message' => $message,
            'responded_at' => now()
        ]);

        // Create the actual share
        FileShare::create([
            'file_id' => $this->file_id,
            'email' => $this->requester->email,
            'access' => $this->requested_access,
            'shared_by' => $approver->id
        ]);
    }

    public function reject(User $approver, $message = null)
    {
        $this->update([
            'status' => 'rejected',
            'approver_id' => $approver->id,
            'response_message' => $message,
            'responded_at' => now()
        ]);
    }
}
