<?php

namespace App\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * A generic Call For Papers event.
 */
class CFPEvent extends Event
{
    /** @var array */
    private $data;

    /**
     * Constructor
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Returns the data.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Returns the value of a given data key. If there is no value for the key,
     * the default value is returned.
     *
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getDataValue($key, $default = null)
    {
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }
}
