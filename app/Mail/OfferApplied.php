<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Offer;
use App\User;

class OfferApplied extends Mailable
{
    use Queueable, SerializesModels;

    public $offer;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Offer $offer)
    {
        $this->offer = $offer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('有一个新在线見積需要处理 ID:' . $this->offer->id)
                ->view('mails.newoffer_html');
    }
}
