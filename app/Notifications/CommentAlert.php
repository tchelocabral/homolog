<?php

namespace App\Notifications;

use App\Models\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CommentAlert extends Notification
{
    use Queueable;

    protected $userNotif;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($dadosUser)  {
        //

        $this->userNotif = $dadosUser;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        $userNotif = $this->userNotif;
        return (new MailMessage)
            ->error()
            ->from(env('MAIL_USERNAME') ?? '011brasil@011brasil.com.br') #'criacao@criacaocia.com.br'
            ->subject('No Reply - Full Freela')
            ->line(utf8_encode($userNotif->mesagem["msg"]))
            ->action( __('notif.Visualizar'), $userNotif->rota)
            ->markdown('emails.notificacao_job', ['userNotif' => $userNotif]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
            'titulo'    =>  utf8_encode($this->userNotif->mensagem["titulo"]),
            'message'   =>  utf8_encode($this->userNotif->mensagem["msg"]),
            'rota'      =>  $this->userNotif->rota,
            'nome'      =>  $this->userNotif->destinatario['name']
        ];
    }
}
