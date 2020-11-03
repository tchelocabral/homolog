<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserRegisteredSuccessfully extends Notification
{
    use Queueable;

    /**
     * @var User
     *
     */
    protected $user;

    /**
     * Create a new notification instance.
     * @param User $user
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        
        return (new MailMessage)
            ->from(env('MAIL_USERNAME') ?? '011brasil@011brasil.com.br')
            ->subject($this->user->name ? 'FULLFREELA :: ' .__('notif.Seja bem vindo').', '.$this->user->name.'!' : 'FULLFREELA :: ' .__('Seja bem vindo').'!')
            ->greeting(sprintf(__('notif.Olá') .', %s', $this->user->name))
            ->line(__('notif.seu cadastro no FULLFREELA foi realizado com sucesso! Clique no botão para ativar sua conta.'))
            ->action(__('notif.Ativar Conta'), route('activate.user', $this->user->activation_code));
            #->line('Obrigado por usar o Sistema Fullfreela de Jobs.');
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
        ];
    }
}
