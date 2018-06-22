<?php

namespace App\EventsSubscriber;

use App\Entity\Company;
use App\Events;
use App\Mailer\Mailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class CompanyNotificationSubscriber implements EventSubscriberInterface
{
    /**
     * @var Mailer
     */
    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::COMPANY_CREATED => 'sendEmail',
        ];
    }

    public function sendEmail(GenericEvent $event)
    {
        /** @var Company $company */
        $company = $event->getSubject();
        $this->mailer->sendEmailCompanyCreated($company);
    }
}
