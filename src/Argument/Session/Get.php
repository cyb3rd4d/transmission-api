<?php

namespace Martial\Transmission\API\Argument\Session;

interface Get extends Accessor
{
    /**
     * Value type: number
     */
    CONST BLOCKLIST_SIZE = 'blocklist-size';

    /**
     * Value type: string
     */
    CONST CONFIG_DIR = 'config-dir';

    /**
     * Value type: number
     */
    CONST RPC_VERSION = 'rpc-version';

    /**
     * Value type: number
     */
    CONST RPC_VERSION_MINIMUM = 'rpc-version-minimum';

    /**
     * Value type: string
     */
    CONST VERSION = 'version';
}
