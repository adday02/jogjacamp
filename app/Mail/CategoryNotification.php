<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CategoryNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $category;
    public $action;

    public function __construct($category, $action)
    {
        $this->category = $category;
        $this->action = $action;
    }

    public function build()
    {
        return $this->view('emails.category_notification')
                    ->with([
                        'categoryName' => $this->category->name,
                        'action' => $this->action,
                    ]);
    }
}
