<?php

namespace Martial\Transmission\API;

class CSRFException extends \Exception
{
    /**
     * @var string
     */
    private $sessionId;

    /**
     * Sets the session ID of the last request.
     *
     * @param string $sessionId
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
    }

    /**
     * Returns the last session ID of a request.
     *
     * @return string
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }
}
