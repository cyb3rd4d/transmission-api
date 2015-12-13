<?php

namespace Martial\Transmission\API;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

class RpcClient implements TransmissionAPI
{
    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @var string
     */
    private $rpcUsername;

    /**
     * @var string
     */
    private $rpcPassword;

    /**
     * RpcClient constructor.
     *
     * @param Client $httpClient
     * @param string $rpcUsername
     * @param string $rpcPassword
     */
    public function __construct(Client $httpClient, $rpcUsername, $rpcPassword)
    {
        $this->httpClient = $httpClient;
        $this->rpcUsername = $rpcUsername;
        $this->rpcPassword = $rpcPassword;
    }

    /**
     * Starts the given torrents (all the torrents if no IDs are provided).
     *
     * @param string $sessionId
     * @param int[] $ids
     * @throws TransmissionException
     * @throws CSRFException
     */
    public function torrentStart($sessionId, array $ids)
    {
        try {
            $response = $this
                ->httpClient
                ->post('', [
                    'body' => $this->buildRequestBody('torrent-start', ['ids' => $ids]),
                    'auth' => [$this->rpcUsername, $this->rpcPassword],
                    'headers' => [
                        'X-Transmission-Session-Id' => $sessionId
                    ]
                ]);

            $responseBody = json_decode($response->getBody(), true);

            if ($responseBody['result'] === 'error') {
                throw new TransmissionException('The Transmission RPC API returned an error.');
            }
        } catch (ClientException $e) {
            if (409 === $e->getCode()) {
                throw new CSRFException('Invalid transmission session ID.', 0 , $e);
            }
        } catch (RequestException $e) {
            throw new TransmissionException('Transmission request error.', 0, $e);
        }
    }

    /**
     * Starts now the given torrents (all the torrents if no IDs are provided).
     *
     * @param string $sessionId
     * @param int[] $ids
     * @throws TransmissionException
     * @throws CSRFException
     */
    public function torrentStartNow($sessionId, array $ids)
    {
        // TODO: Implement torrentStartNow() method.
    }

    /**
     * Stops the given torrents (all the torrents if no IDs are provided).
     *
     * @param string $sessionId
     * @param int[] $ids
     * @throws TransmissionException
     * @throws CSRFException
     */
    public function torrentStop($sessionId, array $ids)
    {
        // TODO: Implement torrentStop() method.
    }

    /**
     * Verifies the given torrents (all the torrents if no IDs are provided).
     *
     * @param string $sessionId
     * @param int[] $ids
     * @throws TransmissionException
     * @throws CSRFException
     */
    public function torrentVerify($sessionId, array $ids)
    {
        // TODO: Implement torrentVerify() method.
    }

    /**
     * Reannonces the given torrents (all the torrents if no IDs are provided).
     *
     * @param string $sessionId
     * @param int[] $ids
     * @throws TransmissionException
     * @throws CSRFException
     */
    public function torrentReannounce($sessionId, array $ids)
    {
        // TODO: Implement torrentReannounce() method.
    }

    /**
     * Sets the given method with the given value to the given torrent IDs.
     * The available methods are defined in the constants of the interface Martial\Transmission\Argument\Torrent\Set.
     * Each of them has a block of documentation to know the accepted value type.
     * Using an empty array for "files-wanted", "files-unwanted", "priority-high", "priority-low", or
     * "priority-normal" is shorthand for saying "all files".
     *
     * @param string $sessionId
     * @param array $ids
     * @param string $method
     * @param mixed $value
     * @throws TransmissionException
     * @throws CSRFException
     */
    public function torrentSet($sessionId, array $ids, $method, $value)
    {
        // TODO: Implement torrentSet() method.
    }

    /**
     * Retrieves the data of the given fields for the given torrent IDs.
     * The available fields are defined in the constants of the interface \Martial\Transmission\Argument\Torrent\Get.
     * All torrents are used if the "ids" array is empty.
     * Returns an array of torrents data. Ex:
     * <code>
     * [
     *     [
     *         'id' => 42,
     *         'name' => 'Fedora x86_64 DVD',
     *         'totalSize' => 34983493932,
     *     ],
     *     [
     *         'id' => 1337,
     *         'name' => 'Ubuntu x86_64 DVD',
     *         'totalSize' => 9923890123,
     *     ],
     * ];
     * </code>
     *
     * @param string $sessionId
     * @param array $ids
     * @param array $fields
     * @return array
     * @throws TransmissionException
     * @throws CSRFException
     */
    public function torrentGet($sessionId, array $ids, array $fields = [])
    {
        // TODO: Implement torrentGet() method.
    }

    /**
     * Adds a torrent to the download queue.
     * The available arguments are defined in the constants of the interface \Martial\Transmission\Argument\Torrent\Add.
     * You MUST provide the filename or the metainfo argument in order to add a torrent.
     * Returns an array with the torrent ID, name and hashString fields. Ex:
     * <code>
     * [
     *     'id' => 42,
     *     'name' => 'Fedora x86_64 DVD',
     *     'hashString' => 'fb7bd58d695990c5a8cb4ac04de9a34ad27a5259'
     * ]
     * </code>
     *
     * @param string $sessionId
     * @param array $arguments
     * @return array
     * @throws DuplicateTorrentException
     * @throws TransmissionException
     * @throws CSRFException
     */
    public function torrentAddBy($sessionId, array $arguments)
    {
        // TODO: Implement torrentAddBy() method.
    }

    /**
     * Removes the given torrent IDs from the download queue.
     * All torrents are used if the "ids" array is empty.
     *
     * @param string $sessionId
     * @param array $ids
     * @param bool $deleteLocalData
     * @throws TransmissionException
     * @throws CSRFException
     */
    public function torrentRemove($sessionId, array $ids, $deleteLocalData = false)
    {
        // TODO: Implement torrentRemove() method.
    }

    /**
     * Moves the given torrent IDs.
     * If $move is set to true, move from previous location. Otherwise, search "location" for files.
     * All torrents are used if the "ids" array is empty.
     *
     * @param string $sessionId
     * @param array $ids
     * @param string $location
     * @param bool $move
     * @throws TransmissionException
     * @throws CSRFException
     */
    public function torrentSetLocation($sessionId, array $ids, $location, $move = false)
    {
        // TODO: Implement torrentSetLocation() method.
    }

    /**
     * Renames a torrent path.
     * Returns an array of torrent data. Ex:
     * <code>
     * [
     *     'id' => 42,
     *     'name' => 'Fedora x86_64 DVD',
     *     'path' => '/path/to/torrent'
     * ]
     * </code>
     *
     * @param string $sessionId
     * @param int $id
     * @param string $oldPath
     * @param string $newPath
     * @return array
     * @throws TransmissionException
     * @throws CSRFException
     */
    public function torrentRenamePath($sessionId, $id, $oldPath, $newPath)
    {
        // TODO: Implement torrentRenamePath() method.
    }

    /**
     * Defines session settings.
     * The settings are listed in the constants of the interface \Martial\Transmission\Argument\Session\Set.
     * Each of them has a block of documentation to know the accepted value type.
     *
     * @param string $sessionId
     * @param array $argumentsWithValues
     * @throws TransmissionException
     * @throws CSRFException
     */
    public function sessionSet($sessionId, array $argumentsWithValues)
    {
        // TODO: Implement sessionSet() method.
    }

    /**
     * Retrieves the session settings.
     * Returns an array of all the settings listed in the interface \Martial\Transmission\Argument\Session\Get.
     *
     * @param string $sessionId
     * @return array
     * @throws TransmissionException
     * @throws CSRFException
     */
    public function sessionGet($sessionId)
    {
        // TODO: Implement sessionGet() method.
    }

    /**
     * Retrieves an array of stats.
     * The keys of the array are listed in the interface \Martial\Transmission\Argument\Session\Stats.
     *
     * @param string $sessionId
     * @return array
     * @throws TransmissionException
     * @throws CSRFException
     */
    public function sessionStats($sessionId)
    {
        // TODO: Implement sessionStats() method.
    }

    /**
     * Updates the blocklist.
     * Returns the blocklist size.
     *
     * @param string $sessionId
     * @return int
     * @throws TransmissionException
     * @throws CSRFException
     */
    public function blocklistUpdate($sessionId)
    {
        // TODO: Implement blocklistUpdate() method.
    }

    /**
     * Checks if the incoming peer port is accessible from the outside world.
     *
     * @param string $sessionId
     * @return bool
     * @throws TransmissionException
     * @throws CSRFException
     */
    public function portTest($sessionId)
    {
        // TODO: Implement portTest() method.
    }

    /**
     * Closes the transmission session.
     *
     * @param string $sessionId
     * @throws TransmissionException
     * @throws CSRFException
     */
    public function sessionClose($sessionId)
    {
        // TODO: Implement sessionClose() method.
    }

    /**
     * Moves the given IDs to the top of the queue.
     *
     * @param string $sessionId
     * @param array $ids
     * @throws TransmissionException
     * @throws CSRFException
     */
    public function queueMoveTop($sessionId, array $ids)
    {
        // TODO: Implement queueMoveTop() method.
    }

    /**
     * Moves the given IDs to previous position in the queue.
     *
     * @param string $sessionId
     * @param array $ids
     * @throws TransmissionException
     * @throws CSRFException
     */
    public function queueMoveDown($sessionId, array $ids)
    {
        // TODO: Implement queueMoveDown() method.
    }

    /**
     * Moves the given IDs to the next potision in the queue.
     *
     * @param string $sessionId
     * @param array $ids
     * @throws TransmissionException
     * @throws CSRFException
     */
    public function queueMoveUp($sessionId, array $ids)
    {
        // TODO: Implement queueMoveUp() method.
    }

    /**
     * Moves the given IDs to the bottom of the queue.
     *
     * @param string $sessionId
     * @param array $ids
     * @throws TransmissionException
     * @throws CSRFException
     */
    public function queueMoveBottom($sessionId, array $ids)
    {
        // TODO: Implement queueMoveBottom() method.
    }

    /**
     * Tests how much free space is available in a client-specified folder.
     * Returns an array of data whose the keys are defined in the interface
     * \Martial\Transmission\Argument\Session\FreeSpace
     *
     * @param string $sessionId
     * @param string $path
     * @return array
     * @throws TransmissionException
     * @throws CSRFException
     */
    public function freeSpace($sessionId, $path)
    {
        // TODO: Implement freeSpace() method.
    }

    /**
     * Returns the JSON representation of a request body.
     *
     * @param string $method
     * @param array $arguments
     * @return string
     */
    private function buildRequestBody($method, array $arguments)
    {
        $body = new \StdClass();
        $body->method = $method;
        $body->arguments = new \StdClass();

        foreach ($arguments as $argument => $value) {
            $body->arguments->$argument = $value;
        }

        return json_encode($body);
    }
}
