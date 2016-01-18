<?php

namespace Martial\Transmission\API\Argument\Session;

interface Accessor
{
    /**
     * Value type: number
     */
    const ALT_SPEED_DOWN = 'alt-speed-down';

    /**
     * Value type: boolean
     */
    const ALT_SPEED_ENABLED = 'alt-speed-enabled';

    /**
     * Value type: number
     */
    const ALT_SPEED_TIME_BEGIN = 'alt-speed-time-begin';

    /**
     * Value type: boolean
     */
    const ALT_SPEED_TIME_ENABLED = 'alt-speed-time-enabled';

    /**
     * Value type: number
     */
    const ALT_SPEED_TIME_END = 'alt-speed-time-end';

    /**
     * Value type: number
     */
    const ALT_SPEED_TIME_DAY = 'alt-speed-time-day';

    /**
     * Value type: number
     */
    const ALT_SPEED_UP = 'alt-speed-up';

    /**
     * Value type: string
     */
    const BLOCKLIST_URL = 'blocklist-url';

    /**
     * Value type: boolean
     */
    const BLOCKLIST_ENABLED = 'blocklist-enabled';

    /**
     * Value type: number
     */
    const CACHE_SIZE_MB = 'cache-size-mb';

    /**
     * Value type: string
     */
    const DOWNLOAD_DIR = 'download-dir';

    /**
     * Value type: number
     */
    const DOWNLOAD_QUEUE_SIZE = 'download-queue-size';

    /**
     * Value type: boolean
     */
    const DOWNLOAD_QUEUE_ENABLED = 'download-queue-enabled';

    /**
     * Value type: boolean
     */
    const DHT_ENABLED = 'dht-enabled';

    /**
     * Value type: string
     */
    const ENCRYPTION = 'encryption';

    /**
     * Value type: number
     */
    const IDLE_SEEDING_LIMIT = 'idle-seeding-limit';

    /**
     * Value type: boolean
     */
    const IDLE_SEEDING_LIMIT_ENABLED = 'idle-seeding-limit-enabled';

    /**
     * Value type: string
     */
    const INCOMPLETE_DIR = 'incomplete-dir';

    /**
     * Value type: boolean
     */
    const INCOMPLETE_DIR_ENABLED = 'incomplete-dir-enabled';

    /**
     * Value type: boolean
     */
    const LPD_ENABLED = 'lpd-enabled';

    /**
     * Value type: number
     */
    const PEER_LIMIT_GLOBAL = 'peer-limit-global';

    /**
     * Value type: number
     */
    const PEER_LIMIT_PER_TORRENT = 'peer-limit-per-torrent';

    /**
     * Value type: boolean
     */
    const PEX_ENABLED = 'pex-enabled';

    /**
     * Value type: number
     */
    const PEER_PORT = 'peer-port';

    /**
     * Value type: boolean
     */
    const PEER_PORT_RANDOM_ON_START = 'peer-port-random-on-start';

    /**
     * Value type: boolean
     */
    const PORT_FORWARDING_ENABLED = 'port-forwarding-enabled';

    /**
     * Value type: boolean
     */
    const QUEUE_STALLED_ENABLED = 'queue-stalled-enabled';

    /**
     * Value type: number
     */
    const QUEUE_STALLED_MINUTES = 'queue-stalled-minutes';

    /**
     * Value type: boolean
     */
    const RENAMED_PARTIAL_FILES = 'rename-partial-files';

    /**
     * Value type: string
     */
    const SCRIPT_TORRENT_DONE_FILENAME = 'script-torrent-done-filename';

    /**
     * Value type: boolean
     */
    const SCRIPT_TORRENT_DONE_ENABLED = 'script-torrent-done-enabled';

    /**
     * Value type: double
     */
    const SEED_RATIO_LIMIT = 'seedRatioLimit';

    /**
     * Value type: boolean
     */
    const SEED_RATIO_LIMITED = 'seedRatioLimited';

    /**
     * Value type: number
     */
    const SEED_QUEUE_SIZE = 'seed-queue-size';

    /**
     * Value type: boolean
     */
    const SEED_QUEUE_ENABLED = 'seed-queue-enabled';

    /**
     * Value type: number
     */
    const SPEED_LIMIT_DOWN = 'speed-limit-down';

    /**
     * Value type: boolean
     */
    const SPEED_LIMIT_DOWN_ENABLED = 'speed-limit-down-enabled';

    /**
     * Value type: number
     */
    const SPEED_LIMIT_UP = 'speed-limit-up';

    /**
     * Value type: boolean
     */
    const SPEED_LIMIT_UP_ENABLED = 'speed-limit-up-enabled';

    /**
     * Value type: boolean
     */
    const START_ADDED_TORRENTS = 'start-added-torrents';

    /**
     * Value type: boolean
     */
    const TRASH_ORIGINAL_TORRENT_FILES = 'trash-original-torrent-files';

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
    const UNITS = 'units';

    /**
     * Value type: boolean
     */
    const UTP_ENABLED = 'utp-enabled';
}
