<?php

namespace App\Notification;

use Swift_Mailer;
use Symfony\Component\Translation\TranslatorInterface as Translator;

/**
 * Class Mailer.
 */
class Mailer
{
    /** @var Swift_Mailer */
    private $mailer;

    /** @var Translator */
    private $translator;

    /**
     * Constructor
     *
     * @param Swift_Mailer $mailer
     * @param Translator   $translator
     */
    public function __construct(Swift_Mailer $mailer, Translator $translator)
    {
        $this->mailer = $mailer;
        $this->translator = $translator;
    }

    public function sendMail(array $from, array $to, $subject, $body)
    {
        /** @var \Swift_Message $message */
        $message = $this->mailer->createMessage();

        $message->setFrom($from);
        $message->setTo($to);
        $message->setSubject($subject);
        $message->setBody($body);
        $message->setReplyTo(['info@phpday.uy' => 'PHPday UY']);
        $message->addBcc('isma@phpday.uy', 'Ismael Ambrosi');

        $this->mailer->send($message);
    }

    public function sendMailWithTranslatedTexts(
        array $from,
        array $to,
        $subjectId,
        $bodyId,
        array $replacements = [],
        $domain = null
    ) {
        $subject = $this->translator->trans($subjectId, $replacements, $domain);
        $body = $this->translator->trans($bodyId, $replacements, $domain);

        $this->sendMail($from, $to, $subject, $body);
    }
}
