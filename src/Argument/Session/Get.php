<?php

namespace Martial\Transmission\API\Argument\Session;

interface Get extends Accessor
{
    /**
     * Value type: number
     */
    const BLOCKLIST_SIZE = 'blocklist-size';

    /**
     * Value type: string
     */
    const CONFIG_DIR = 'config-dir';

    /**
     * Value type: number
     */
    const RPC_VERSION = 'rpc-version';

    /**
     * Value type: number
     */
    const RPC_VERSION_MINIMUM = 'rpc-version-minimum';

    /**
     * Value type: string
     */
    const VERSION = 'version';
}
