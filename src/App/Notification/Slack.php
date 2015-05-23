<?php

namespace App\Notification;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * Class Slack.
 */
class Slack
{
    /** @var string */
    private $hookUrl;

    /** @var bool */
    private $enabled;

    /**
     * Constructor
     *
     * @param string $hookUrl
     */
    public function __construct($hookUrl)
    {
        $this->hookUrl = $hookUrl;
        $this->enabled = !empty($this->hookUrl);
    }

    public function disable()
    {
        $this->enabled = false;
    }

    public function sendHook($text, array $options = [])
    {
        if (!$this->enabled) {
            return;
        }

        try {
            $client = new Client([
                'timeout' => 1,
                'connect_timeout' => 1,
            ]);

            $payload = array_merge($options, [
                'text' => $text,
            ]);

            $client->post($this->hookUrl, ['json' => $payload]);
        } catch (RequestException $exception) {
        }
    }
}
