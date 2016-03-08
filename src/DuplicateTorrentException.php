<?php

namespace Martial\Transmission\API;

class DuplicateTorrentException extends \Exception
{
    /**
     * @var int
     */
    private $torrentId;

    /**
     * @var string
     */
    private $torrentName;

    /**
     * @var string
     */
    private $torrentHashString;

    /**
     * @return int
     */
    public function getTorrentId()
    {
        return $this->torrentId;
    }

    /**
     * @param int $torrentId
     */
    public function setTorrentId($torrentId)
    {
        $this->torrentId = $torrentId;
    }

    /**
     * @return string
     */
    public function getTorrentName()
    {
        return $this->torrentName;
    }

    /**
     * @param string $torrentName
     */
    public function setTorrentName($torrentName)
    {
        $this->torrentName = $torrentName;
    }

    /**
     * @return string
     */
    public function getTorrentHashString()
    {
        return $this->torrentHashString;
    }

    /**
     * @param string $torrentHashString
     */
    public function setTorrentHashString($torrentHashString)
    {
        $this->torrentHashString = $torrentHashString;
    }
}
