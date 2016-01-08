<?php

// Load composer

$rpcUri = 'http://42.42.42.42:9091/transmission/rpc';
$rpcUsername = 'transmission';
$rpcPassword = 'transmission';
$newTorrentFile = '/tmp/debian-8.2.0-amd64-CD-1.iso.torrent';

$guzzle = new GuzzleHttp\Client(['base_uri' => $rpcUri]);
$api = new \Martial\Transmission\API\RpcClient($guzzle, $rpcUsername, $rpcPassword);

/**
 * @param array $torrentList
 */
function printTorrentData(array $torrentList)
{
    foreach ($torrentList as $torrentData) {
        printf(
            'The status of the torrent "%s" with the ID %d is "%s".',
            $torrentData[\Martial\Transmission\API\Argument\Torrent\Get::NAME],
            $torrentData[\Martial\Transmission\API\Argument\Torrent\Get::ID],
            $torrentData[\Martial\Transmission\API\Argument\Torrent\Get::STATUS]
        );

        echo PHP_EOL;
    }
}

/**
 * @param \Martial\Transmission\API\TransmissionAPI $api
 * @param $sessionId
 * @param array $ids
 * @return array
 */
function getTorrentData(\Martial\Transmission\API\TransmissionAPI $api, $sessionId, array $ids)
{
    $torrentList = $api->torrentGet($sessionId, $ids, [
        \Martial\Transmission\API\Argument\Torrent\Get::ID,
        \Martial\Transmission\API\Argument\Torrent\Get::NAME,
        \Martial\Transmission\API\Argument\Torrent\Get::STATUS
    ]);


    return $torrentList;
}

/**
 * @param array $torrentList
 */
function checkNotEmptyList(array $torrentList)
{
    if (empty($torrentList)) {
        echo 'No torrents found.' . PHP_EOL;
        exit(0);
    }
}

$sessionId = '';

// Fetching a new session ID
try {
    $api->sessionGet($sessionId);
} catch (\Martial\Transmission\API\CSRFException $e) {
    $sessionId = $e->getSessionId();
}

// Adding a new torrent to the download queue
$torrentData = $api->torrentAdd($sessionId, [
    \Martial\Transmission\API\Argument\Torrent\Add::FILENAME => $newTorrentFile
]);

printf(
    'New torrent "%s" with ID %d added:',
    $torrentData[\Martial\Transmission\API\Argument\Torrent\Get::NAME],
    $torrentData[\Martial\Transmission\API\Argument\Torrent\Get::ID]
);
echo PHP_EOL;

$torrentList = getTorrentData($api, $sessionId, []);
checkNotEmptyList($torrentList);
echo 'Torrent list:' . PHP_EOL;
printTorrentData($torrentList);

// Stopping the first torrent
$api->torrentStop($sessionId, [$torrentList[0][\Martial\Transmission\API\Argument\Torrent\Get::ID]]);

sleep(1);

$torrentList = getTorrentData($api, $sessionId, []);
checkNotEmptyList($torrentList);
echo 'Torrent list:' . PHP_EOL;
printTorrentData($torrentList);

// Starting the first torrent
$api->torrentStart($sessionId, [$torrentList[0][\Martial\Transmission\API\Argument\Torrent\Get::ID]]);

sleep(1); // The transmission API is not real time

$torrentList = getTorrentData($api, $sessionId, [$torrentList[0][\Martial\Transmission\API\Argument\Torrent\Get::ID]]);
checkNotEmptyList($torrentList);
echo 'Torrent list:' . PHP_EOL;
printTorrentData($torrentList);

// Removing the first torrent
$api->torrentRemove($sessionId, [$torrentList[0][\Martial\Transmission\API\Argument\Torrent\Get::ID]], true);

sleep(1);

$torrentList = getTorrentData($api, $sessionId, []);
checkNotEmptyList($torrentList);
echo 'Torrent list:' . PHP_EOL;
printTorrentData($torrentList);
