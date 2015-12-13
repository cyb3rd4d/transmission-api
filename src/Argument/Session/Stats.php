<?php

namespace Martial\Transmission\API\Argument\Session;

interface Stats
{
    const ACTIVE_TORRENT_COUNT = 'activeTorrentCount';
    const DOWNLOAD_SPEED = 'downloadSpeed';
    const PAUSED_TORRENT_COUNT = 'pausedTorrentCount';
    const TORRENT_COUNT = 'torrentCount';
    const UPLOAD_SPEED = 'uploadSpeed';
    const CUMULATIVE_STATS = 'cumulative-stats';
    const CURRENT_STATS = 'current-stats';
}
