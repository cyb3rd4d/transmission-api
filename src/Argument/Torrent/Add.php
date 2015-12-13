<?php

namespace Martial\Transmission\API\Argument\Torrent;

interface Add
{
    const COOKIES = 'cookies';
    const DOWNLOAD_DIR = 'download-dir';
    const FILENAME = 'filename';
    const METAINFO = 'metainfo';
    const PAUSED = 'paused';
    const PEER_LIMIT = 'peer-limit';
    const BANDWIDTH_PRIORITY = 'bandwidthPriority';
    const FILES_WANTED = 'files-wanted';
    const FILES_UNWANTED = 'files-unwanted';
    const PRIORITY_HIGH = 'priority-high';
    const PRIORITY_LOW = 'priority-low';
    const PRIORITY_NORMAL = 'priority-normal';
}
