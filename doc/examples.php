<?php

// Load composer

use GuzzleHttp\Client;
use Martial\Transmission\API\Argument\Torrent\Add;
use Martial\Transmission\API\Argument\Torrent\Get;
use Martial\Transmission\API\CSRFException;
use Martial\Transmission\API\RpcClient;
use Martial\Transmission\API\TorrentIdList;
use Martial\Transmission\API\TransmissionAPI;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

$rpcUri = 'http://42.42.42.42:9091/transmission/rpc';
$rpcUsername = 'transmission';
$rpcPassword = 'transmission';
$newTorrentFile = '/tmp/debian-8.2.0-amd64-CD-1.iso.torrent';

$guzzle = new Client(['base_uri' => $rpcUri]);
$logger = new Logger('transmission');
$logger->pushHandler(new StreamHandler('php://stdout'));

$api = new RpcClient($guzzle, $rpcUsername, $rpcPassword, $logger);

/**
 * @param array $torrentList
 */
function printTorrentData(array $torrentList)
{
    foreach ($torrentList as $torrentData) {
        printf(
            'The status of the torrent "%s" with the ID %d is "%s".',
            $torrentData[Get::NAME],
            $torrentData[Get::ID],
            $torrentData[Get::STATUS]
        );

        echo PHP_EOL;
    }
}

/**
 * @param TransmissionAPI $api
 * @param $sessionId
 * @param array $ids
 * @return array
 */
function getTorrentData(TransmissionAPI $api, $sessionId, array $ids)
{
    $torrentList = $api->torrentGet($sessionId, new TorrentIdList($ids), [
        Get::ID,
        Get::NAME,
        Get::STATUS
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
} catch (CSRFException $e) {
    $sessionId = $e->getSessionId();
}

// Adding a new torrent to the download queue
$torrentData = $api->torrentAdd($sessionId, [
    Add::FILENAME => $newTorrentFile
]);

printf(
    'New torrent "%s" with ID %d added:',
    $torrentData[Get::NAME],
    $torrentData[Get::ID]
);
echo PHP_EOL;

$torrentList = getTorrentData($api, $sessionId, []);
checkNotEmptyList($torrentList);
echo 'Torrent list:' . PHP_EOL;
printTorrentData($torrentList);

// Stopping the first torrent
$api->torrentStop($sessionId, new TorrentIdList(
    [$torrentList[0][Get::ID]]
));

sleep(1); // The transmission API is not real time

$torrentList = getTorrentData($api, $sessionId, []);
checkNotEmptyList($torrentList);
echo 'Torrent list:' . PHP_EOL;
printTorrentData($torrentList);

// Starting the first torrent
$api->torrentStart($sessionId, new TorrentIdList([$torrentList[0][Get::ID]]));

sleep(1);

$torrentList = getTorrentData($api, $sessionId, [$torrentList[0][Get::ID]]);
checkNotEmptyList($torrentList);
echo 'Torrent list:' . PHP_EOL;
printTorrentData($torrentList);

// Removing the first torrent
$api->torrentRemove($sessionId, new TorrentIdList([$torrentList[0][Get::ID]]), true);

sleep(1);

$torrentList = getTorrentData($api, $sessionId, []);
checkNotEmptyList($torrentList);
echo 'Torrent list:' . PHP_EOL;
printTorrentData($torrentList);
