<?php

namespace Martial\Transmission\API\Argument\Torrent;

interface Set
{
    /**
     * Value type: int
     */
    const BANDWIDTH_PRIORITY = 'bandwidthPriority';

    /**
     * Value type: int
     */
    const DOWNLOAD_LIMIT = 'downloadLimit';

    /**
     * Value type: bool
     */
    const DOWNLOAD_LIMITED = 'downloadLimited';

    /**
     * Value type: array
     */
    const FILES_WANTED = 'files-wanted';

    /**
     * Value type: array
     */
    const FILES_UNWANTED = 'files-unwanted';

    /**
     * Value type: bool
     */
    const HONORS_SESSION_LIMITS = 'honorsSessionLimits';

    /**
     * Value type: array
     */
    const IDS = 'ids';

    /**
     * Value type: string
     */
    const LOCATION = 'location';

    /**
     * Value type: number
     */
    const PEER_LIMIT = 'peer-limit';

    /**
     * Value type: array
     */
    const PRIORITY_HIGH = 'priority-high';

    /**
     * Value type: array
     */
    const PRIORITY_LOW = 'priority-low';

    /**
     * Value type: array
     */
    const PRIORITY_NORMAL = 'priority-normal';

    /**
     * Value type: int
     */
    const QUEUE_POSITION = 'queuePosition';

    /**
     * Value type: int
     */
    const SEED_IDLE_LIMIT = 'seedIdleLimit';

    /**
     * Value type: int
     */
    const SEED_IDLE_MODE = 'seedIdleMode';

    /**
     * Value type: float
     */
    const SEED_RATIO_LIMIT = 'seedRatioLimit';

    /**
     * Value type: int
     */
    const SEED_RATIO_MODE = 'seedRatioMode';

    /**
     * Value type: array
     */
    const TRACKER_ADD = 'trackerAdd';

    /**
     * Value type: array
     */
    const TRACKER_REMOVE = 'trackerRemove';

    /**
     * Value type: array
     */
    const TRACKER_REPLACE = 'trackerReplace';

    /**
     * Value type: int
     */
    const UPLOAD_LIMIT = 'uploadLimit';

    /**
     * Value type: bool
     */
    const UPLOAD_LIMITED = 'uploadLimited';
}
