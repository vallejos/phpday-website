<?php

namespace App\Event\Subscriber;

use App\Event\CFPEvent;
use App\Notification\Slack;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class NotifyReceivedCFPSubscriber.
 */
class NotifyReceivedCFPSubscriber implements EventSubscriberInterface
{
    /** @var Slack */
    private $slack;

    /**
     * Constructor
     *
     * @param Slack $slack
     */
    public function __construct(Slack $slack)
    {
        $this->slack = $slack;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return ['cfp.received' => 'notifySlack'];
    }

    public function notifySlack(CFPEvent $event)
    {
        $text = sprintf('Nueva propuesta de charla recibida de %s: %s', $event->getDataValue('name', 'Desconocido'), $event->getDataValue('title'));

        $this->slack->sendHook($text, [
            'icon_emoji' => ':panda_face:',
            'username' => 'cfp-bot',
        ]);
    }
}
