<?php
namespace App\Mail;

use App\Models\File;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FileShared extends Mailable
{
    use Queueable, SerializesModels;

    public $file;
    public $link;
    public $sender;

    public function __construct(File $file, $link, $sender)
    {
        $this->file = $file;
        $this->link = $link;
        $this->sender = $sender;
    }

    public function build()
    {
        return $this->subject("A file was shared with you")
            ->view('emails.file_shared');
    }
}
