<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\Guest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $guest;
    public $event;
    public $registrationLink; // Asegúrate de declarar esta variable

    public function __construct(Guest $guest, Event $event)
    {
        $this->guest = $guest;
        $this->event = $event;
        $this->registrationLink = route('guest.confirmation.form', $event->id); // Genera el link aquí
    }

    public function build()
    {
        return $this->subject("Invitación al Evento: {$this->event->title}")
                   ->view('emails.guest_invitation', [
                       'guest' => $this->guest,
                       'event' => $this->event,
                       'registrationLink' => $this->registrationLink // Pasa explícitamente la variable
                   ]);
    }
}