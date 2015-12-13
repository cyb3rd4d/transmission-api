<?php

namespace Martial\Transmission\API;

/**
 * The interface of the Transmission PHP client. Its behavior is as close to the original RPC API as possible. So, for more information about methods or arguments,
 * read the official API documentation.
 *
 * Each of these methods can throws a Martial\Transmission\TransmissionException if the request fails.
 * They also can throw a Martial\Transmission\CSRFException if the session ID, provided as the first argument of each
 * method, is not valid. You can easily retrieve a new session ID by calling any of the API method with an invalid
 * session ID and catch the Martial\Transmission\CSRFException, which contains a method getSessionId():
 *
 * <code>
 * try {
 *     $api->torrentGet('', [], [\Martial\Transmission\Argument\Torrent\Get::ID]);
 * } catch (Martial\Transmission\CSRFException $e) {
 *     $freshSessionId = $e->getSessionId();
 * }
 * </code>
 *
 * @see https://trac.transmissionbt.com/browser/trunk/extras/rpc-spec.txt
 */
interface TransmissionAPI
{
    /**
     * Starts the given torrents (all the torrents if no IDs are provided).
     *
     * @param string $sessionId
     * @param int[] $ids
     * @throws TransmissionException
     * @throws CSRFException
     */
    public function torrentStart($sessionId, array $ids);

    /**
     * Starts now the given torrents (all the torrents if no IDs are provided).
     *
     * @param string $sessionId
     * @param int[] $ids
     * @throws TransmissionException
     * @throws CSRFException
     */
    public function torrentStartNow($sessionId, array $ids);

    /**
     * Stops the given torrents (all the torrents if no IDs are provided).
     *
     * @param string $sessionId
     * @param int[] $ids
     * @throws TransmissionException
     * @throws CSRFException
     */
    public function torrentStop($sessionId, array $ids);

    /**
     * Verifies the given torrents (all the torrents if no IDs are provided).
     *
     * @param string $sessionId
     * @param int[] $ids
     * @throws TransmissionException
     * @throws CSRFException
     */
    public function torrentVerify($sessionId, array $ids);

    /**
     * Reannonces the given torrents (all the torrents if no IDs are provided).
     *
     * @param string $sessionId
     * @param int[] $ids
     * @throws TransmissionException
     * @throws CSRFException
     */
    public function torrentReannounce($sessionId, array $ids);

    /**
     * Applies the given arguments with their values to the given torrent IDs.
     * The available methods are defined in the constants of the interface Martial\Transmission\Argument\Torrent\Set.
     * Each of them has a block of documentation to know the accepted value type.
     * Using an empty array for "files-wanted", "files-unwanted", "priority-high", "priority-low", or
     * "priority-normal" is shorthand for saying "all files". Ex:
     * <code>
     * $client->torrentSet('iefjzo234fez', [42, 1337], ['downloadLimit' => 200]);
     * </code>
     *
     * @param string $sessionId
     * @param array $ids
     * @param array $argumentsWithValues
     * @throws TransmissionException
     * @throws CSRFException
     */
    public function torrentSet($sessionId, array $ids, array $argumentsWithValues);

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
    public function torrentGet($sessionId, array $ids, array $fields = []);

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
    public function torrentAddBy($sessionId, array $arguments);

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
    public function torrentRemove($sessionId, array $ids, $deleteLocalData = false);

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
    public function torrentSetLocation($sessionId, array $ids, $location, $move = false);

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
    public function torrentRenamePath($sessionId, $id, $oldPath, $newPath);

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
    public function sessionSet($sessionId, array $argumentsWithValues);

    /**
     * Retrieves the session settings.
     * Returns an array of all the settings listed in the interface \Martial\Transmission\Argument\Session\Get.
     *
     * @param string $sessionId
     * @return array
     * @throws TransmissionException
     * @throws CSRFException
     */
    public function sessionGet($sessionId);

    /**
     * Retrieves an array of stats.
     * The keys of the array are listed in the interface \Martial\Transmission\Argument\Session\Stats.
     *
     * @param string $sessionId
     * @return array
     * @throws TransmissionException
     * @throws CSRFException
     */
    public function sessionStats($sessionId);

    /**
     * Updates the blocklist.
     * Returns the blocklist size.
     *
     * @param string $sessionId
     * @return int
     * @throws TransmissionException
     * @throws CSRFException
     */
    public function blocklistUpdate($sessionId);

    /**
     * Checks if the incoming peer port is accessible from the outside world.
     *
     * @param string $sessionId
     * @return bool
     * @throws TransmissionException
     * @throws CSRFException
     */
    public function portTest($sessionId);

    /**
     * Closes the transmission session.
     *
     * @param string $sessionId
     * @throws TransmissionException
     * @throws CSRFException
     */
    public function sessionClose($sessionId);

    /**
     * Moves the given IDs to the top of the queue.
     *
     * @param string $sessionId
     * @param array $ids
     * @throws TransmissionException
     * @throws CSRFException
     */
    public function queueMoveTop($sessionId, array $ids);

    /**
     * Moves the given IDs to previous position in the queue.
     *
     * @param string $sessionId
     * @param array $ids
     * @throws TransmissionException
     * @throws CSRFException
     */
    public function queueMoveDown($sessionId, array $ids);

    /**
     * Moves the given IDs to the next potision in the queue.
     *
     * @param string $sessionId
     * @param array $ids
     * @throws TransmissionException
     * @throws CSRFException
     */
    public function queueMoveUp($sessionId, array $ids);

    /**
     * Moves the given IDs to the bottom of the queue.
     *
     * @param string $sessionId
     * @param array $ids
     * @throws TransmissionException
     * @throws CSRFException
     */
    public function queueMoveBottom($sessionId, array $ids);

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
    public function freeSpace($sessionId, $path);
}
