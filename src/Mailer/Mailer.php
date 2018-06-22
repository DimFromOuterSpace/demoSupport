<?php

namespace App\Mailer;

use App\Entity\Company;
use Monolog\Logger;
use Symfony\Component\Translation\TranslatorInterface;

class Mailer
{
    /**
     * @var \Swift_Mailer
     */
    private $swiftMailer;

    /**
     * @var \Twig_Environment
     */
    private $twigEnvironment;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var string
     */
    private $from;

    public function __construct(
        \Swift_Mailer $swiftMailer,
        \Twig_Environment $twigEnvironment,
        TranslatorInterface $translator,
        Logger $logger,
        string $from
    ) {
        $this->swiftMailer = $swiftMailer;
        $this->twigEnvironment = $twigEnvironment;
        $this->translator = $translator;
        $this->logger = $logger;
        $this->from = $from;
    }

    public function sendEmailCompanyCreated(Company $company)
    {
        $this->sendEmail(
            $this->translator->trans('mail.company.created.subject'),
            $company->getMailContact(),
            $this->twigEnvironment->render('mail/company/created.html.twig', ['company' => $company]),
            $this->twigEnvironment->render('mail/company/created.txt.twig', ['company' => $company])
        );
    }

    /**
     * @param string       $subject
     * @param string|array $to
     * @param string       $body
     */
    private function sendEmail(string $subject, $to, string $bodyHtml, $bodyText = null)
    {
        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setFrom($this->from)
            ->setTo($to)
            ->setBody($bodyHtml, 'text/html');
        if ($bodyText) {
            $message->addPart($bodyText, 'text/plain');
        }

        if (0 != $this->swiftMailer->send($message)) {
            $this->logger->addInfo('Un e-mail a été envoyé.');

            return;
        }
        $this->logger->addInfo("Erreur d'envoi d'e-mail.");
    }
}
