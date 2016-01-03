<?php

namespace Martial\Transmission\API;

class TransmissionException extends \Exception
{
    /**
     * @var string
     */
    private $result;

    /**
     * @return string
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param string $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }
}
