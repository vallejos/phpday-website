<?php

namespace App\Event\Subscriber;

use App\Event\CFPEvent;
use App\Notification\Mailer;
use App\Notification\Slack;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class NotifyReceivedCFPSubscriber.
 */
class NotifyReceivedCFPSubscriber implements EventSubscriberInterface
{
    /** @var Slack */
    private $slack;

    /** @var Mailer */
    private $mailer;

    /**
     * Constructor
     *
     * @param Slack  $slack
     * @param Mailer $mailer
     */
    public function __construct(Slack $slack, Mailer $mailer)
    {
        $this->slack = $slack;
        $this->mailer = $mailer;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return ['cfp.received' => [['notifySlack'], ['notifySender']]];
    }

    public function notifySlack(CFPEvent $event)
    {
        $text = sprintf('Nueva propuesta de charla recibida de %s: %s', $event->getDataValue('name', 'Desconocido'), $event->getDataValue('title'));

        $this->slack->sendHook($text, [
            'icon_emoji' => ':panda_face:',
            'username' => 'cfp-bot',
        ]);
    }

    public function notifySender(CFPEvent $event)
    {
        $this->mailer->sendMailWithTranslatedTexts(
            ['noreply@phpday.uy' => 'PHPday UY'],
            [$event->getDataValue('email') => $event->getDataValue('name')],
            'cfp.mail.subject',
            'cfp.mail.body',
            ['%name%' => $event->getDataValue('name')]
        );
    }
}
