<?php

namespace Martial\Transmission\API\Argument\Session;

interface Common
{
    /**
     * Value type: number
     */
    CONST ALT_SPEED_DOWN = 'alt-speed-down';

    /**
     * Value type: boolean
     */
    CONST ALT_SPEED_ENABLED = 'alt-speed-enabled';

    /**
     * Value type: number
     */
    CONST ALT_SPEED_TIME_BEGIN = 'alt-speed-time-begin';

    /**
     * Value type: boolean
     */
    CONST ALT_SPEED_TIME_ENABLED = 'alt-speed-time-enabled';

    /**
     * Value type: number
     */
    CONST ALT_SPEED_TIME_END = 'alt-speed-time-end';

    /**
     * Value type: number
     */
    CONST ALT_SPEED_TIME_DAY = 'alt-speed-time-day';

    /**
     * Value type: number
     */
    CONST ALT_SPEED_UP = 'alt-speed-up';

    /**
     * Value type: string
     */
    CONST BLOCKLIST_URL = 'blocklist-url';

    /**
     * Value type: boolean
     */
    CONST BLOCKLIST_ENABLED = 'blocklist-enabled';

    /**
     * Value type: number
     */
    CONST CACHE_SIZE_MB = 'cache-size-mb';

    /**
     * Value type: string
     */
    CONST DOWNLOAD_DIR = 'download-dir';

    /**
     * Value type: number
     */
    CONST DOWNLOAD_QUEUE_SIZE = 'download-queue-size';

    /**
     * Value type: boolean
     */
    CONST DOWNLOAD_QUEUE_ENABLED = 'download-queue-enabled';

    /**
     * Value type: boolean
     */
    CONST DHT_ENABLED = 'dht-enabled';

    /**
     * Value type: string
     */
    CONST ENCRYPTION = 'encryption';

    /**
     * Value type: number
     */
    CONST IDLE_SEEDING_LIMIT = 'idle-seeding-limit';

    /**
     * Value type: boolean
     */
    CONST IDLE_SEEDING_LIMIT_ENABLED = 'idle-seeding-limit-enabled';

    /**
     * Value type: string
     */
    CONST INCOMPLETE_DIR = 'incomplete-dir';

    /**
     * Value type: boolean
     */
    CONST INCOMPLETE_DIR_ENABLED = 'incomplete-dir-enabled';

    /**
     * Value type: boolean
     */
    CONST LPD_ENABLED = 'lpd-enabled';

    /**
     * Value type: number
     */
    CONST PEER_LIMIT_GLOBAL = 'peer-limit-global';

    /**
     * Value type: number
     */
    CONST PEER_LIMIT_PER_TORRENT = 'peer-limit-per-torrent';

    /**
     * Value type: boolean
     */
    CONST PEX_ENABLED = 'pex-enabled';

    /**
     * Value type: number
     */
    CONST PEER_PORT = 'peer-port';

    /**
     * Value type: boolean
     */
    CONST PEER_PORT_RANDOM_ON_START = 'peer-port-random-on-start';

    /**
     * Value type: boolean
     */
    CONST PORT_FORWARDING_ENABLED = 'port-forwarding-enabled';

    /**
     * Value type: boolean
     */
    CONST QUEUE_STALLED_ENABLED = 'queue-stalled-enabled';

    /**
     * Value type: number
     */
    CONST QUEUE_STALLED_MINUTES = 'queue-stalled-minutes';

    /**
     * Value type: boolean
     */
    CONST RENAMED_PARTIAL_FILES = 'rename-partial-files';

    /**
     * Value type: string
     */
    CONST SCRIPT_TORRENT_DONE_FILENAME = 'script-torrent-done-filename';

    /**
     * Value type: boolean
     */
    CONST SCRIPT_TORRENT_DONE_ENABLED = 'script-torrent-done-enabled';

    /**
     * Value type: double
     */
    CONST SEED_RATIO_LIMIT = 'seedRatioLimit';

    /**
     * Value type: boolean
     */
    CONST SEED_RATIO_LIMITED = 'seedRatioLimited';

    /**
     * Value type: number
     */
    CONST SEED_QUEUE_SIZE = 'seed-queue-size';

    /**
     * Value type: boolean
     */
    CONST SEED_QUEUE_ENABLED = 'seed-queue-enabled';

    /**
     * Value type: number
     */
    CONST SPEED_LIMIT_DOWN = 'speed-limit-down';

    /**
     * Value type: boolean
     */
    CONST SPEED_LIMIT_DOWN_ENABLED = 'speed-limit-down-enabled';

    /**
     * Value type: number
     */
    CONST SPEED_LIMIT_UP = 'speed-limit-up';

    /**
     * Value type: boolean
     */
    CONST SPEED_LIMIT_UP_ENABLED = 'speed-limit-up-enabled';

    /**
     * Value type: boolean
     */
    CONST START_ADDED_TORRENTS = 'start-added-torrents';

    /**
     * Value type: boolean
     */
    CONST TRASH_ORIGINAL_TORRENT_FILES = 'trash-original-torrent-files';

    /**
     * Value type: array
     * Array keys:
     * - speed-units: array - 4 strings: KB/s, MB/s, GB/s, TB/s
     * - speed-bytes: number - number of bytes in a KB (1000 for kB; 1024 for KiB)
     * - size-units: array - 4 strings: KB/s, MB/s, GB/s, TB/s
     * - size-bytes: number - number of bytes in a KB (1000 for kB; 1024 for KiB)
     * - memory-units: array - 4 strings: KB/s, MB/s, GB/s, TB/s
     * - memory-bytes: number - number of bytes in a KB (1000 for kB; 1024 for KiB)
     */
    CONST UNITS = 'units';

    /**
     * Value type: boolean
     */
    CONST UTP_ENABLED = 'utp-enabled';
}
