<?php

namespace ProfileBundle\EventListener;

use Swift_Mailer;
use Symfony\Component\Templating\EngineInterface;
use ProfileBundle\Entity\AccountWasRegistered;

class SendMailOnRegister
{
    private $mailer;
    private $templating;

    public function __construct(Swift_Mailer $mailer, EngineInterface $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    public function onAccountRegistration(AccountWasRegistered $event)
    {
        $this->sendConfirmationEmail(
            $event->accountId,
            $event->email,
            $event->name
        );
    }

    private function sendConfirmationEmail($accountId, $email, $name)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject(sprintf(
                'Thanks for registering, %s',
                $name
            ))
            ->setTo($email)
            ->setBody(
                $this->templating->render(':email:registration.html.twig', [
                    'accountId' => $accountId,
                    'name' => $name,
                ]),
                'text/html'
            )
        ;

        $this->mailer->send($message);
    }
}
