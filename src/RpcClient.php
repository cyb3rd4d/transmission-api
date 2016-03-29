<?php

namespace Martial\Transmission\API;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Martial\Transmission\API\Argument\Torrent\Add;
use Psr\Log\LoggerInterface;

class RpcClient implements TransmissionAPI
{
    /**
     * @var ClientInterface
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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * RpcClient constructor.
     *
     * @param ClientInterface $httpClient
     * @param string $rpcUsername
     * @param string $rpcPassword
     * @param LoggerInterface $logger
     */
    public function __construct(ClientInterface $httpClient, $rpcUsername, $rpcPassword, LoggerInterface $logger = null)
    {
        $this->httpClient = $httpClient;
        $this->rpcUsername = $rpcUsername;
        $this->rpcPassword = $rpcPassword;
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function torrentStart($sessionId, TorrentIdList $ids)
    {
        $this->sendRequest($sessionId, $this->buildRequestBody('torrent-start', ['ids' => $ids->getList()]));
    }

    /**
     * @inheritdoc
     */
    public function torrentStartNow($sessionId, TorrentIdList $ids)
    {
        $this->sendRequest($sessionId, $this->buildRequestBody('torrent-start-now', ['ids' => $ids->getList()]));
    }

    /**
     * @inheritdoc
     */
    public function torrentStop($sessionId, TorrentIdList $ids)
    {
        $this->sendRequest($sessionId, $this->buildRequestBody('torrent-stop', ['ids' => $ids->getList()]));
    }

    /**
     * @inheritdoc
     */
    public function torrentVerify($sessionId, TorrentIdList $ids)
    {
        $this->sendRequest($sessionId, $this->buildRequestBody('torrent-verify', ['ids' => $ids->getList()]));
    }

    /**
     * @inheritdoc
     */
    public function torrentReannounce($sessionId, TorrentIdList $ids)
    {
        $this->sendRequest($sessionId, $this->buildRequestBody('torrent-reannounce', ['ids' => $ids->getList()]));
    }

    /**
     * @inheritdoc
     */
    public function torrentSet($sessionId, TorrentIdList $ids, array $argumentsWithValues)
    {
        $this->sendRequest($sessionId, $this->buildRequestBody(
            'torrent-set',
            array_merge(['ids' => $ids->getList()], $argumentsWithValues)
        ));
    }

    /**
     * @inheritdoc
     */
    public function torrentGet($sessionId, TorrentIdList $ids, array $fields = [])
    {
        if (empty($ids->getList())) {
            $arguments = ['fields' => $fields];
        } else {
            $arguments = array_merge(['ids' => $ids->getList()], ['fields' => $fields]);
        }

        $response = $this->sendRequest($sessionId, $this->buildRequestBody('torrent-get', $arguments));

        return $response['arguments']['torrents'];
    }

    /**
     * @inheritdoc
     */
    public function torrentAdd($sessionId, array $argumentsWithValues)
    {
        if (!isset($argumentsWithValues[Add::FILENAME]) && !isset($argumentsWithValues[Add::METAINFO])) {
            throw new MissingArgumentException(sprintf(
                'You must provide at least the argument "%s" or "%s" to the method %s',
                Add::FILENAME,
                Add::METAINFO,
                __METHOD__
            ));
        }

        $response = $this->sendRequest($sessionId, $this->buildRequestBody(
            'torrent-add',
            $argumentsWithValues
        ));

        return $response['arguments']['torrent-added'];
    }

    /**
     * @inheritdoc
     */
    public function torrentRemove($sessionId, TorrentIdList $ids, $deleteLocalData = false)
    {
        $arguments = ['ids' => $ids->getList()];

        if ($deleteLocalData) {
            $arguments['delete-local-data'] = true;
        }

        $this->sendRequest($sessionId, $this->buildRequestBody('torrent-remove', $arguments));
    }

    /**
     * @inheritdoc
     */
    public function torrentSetLocation($sessionId, TorrentIdList $ids, $location, $move = false)
    {
        $this->sendRequest($sessionId, $this->buildRequestBody('torrent-set-location', [
            'ids' => $ids->getList(),
            'location' => $location,
            'move' => $move
        ]));
    }

    /**
     * @inheritdoc
     */
    public function torrentRenamePath($sessionId, $id, $oldPath, $newPath)
    {
        $response = $this->sendRequest($sessionId, $this->buildRequestBody('torrent-rename-path', [
            'ids' => [$id],
            'path' => $oldPath,
            'name' => $newPath
        ]));

        return $response['arguments'];
    }

    /**
     * @inheritdoc
     */
    public function sessionSet($sessionId, array $argumentsWithValues)
    {
        $invalidArguments = [
            'blocklist-size',
            'config-dir',
            'rpc-version',
            'rpc-version-minimum',
            'version',
        ];

        foreach ($invalidArguments as $invalidArgument) {
            if (isset($argumentsWithValues[$invalidArgument])) {
                throw new TransmissionException(sprintf(
                    'You can not pass the argument "%s" to the sessionSet method.',
                    $invalidArgument
                ));
            }
        }

        $this->sendRequest($sessionId, $this->buildRequestBody('session-set', $argumentsWithValues));
    }

    /**
     * @inheritdoc
     */
    public function sessionGet($sessionId)
    {
        $response = $this->sendRequest($sessionId, $this->buildRequestBody('session-get'));

        return $response['arguments'];
    }

    /**
     * @inheritdoc
     */
    public function sessionStats($sessionId)
    {
        $response = $this->sendRequest($sessionId, $this->buildRequestBody('session-stats'));

        return $response['arguments'];
    }

    /**
     * @inheritdoc
     */
    public function blocklistUpdate($sessionId)
    {
        try {
            $response = $this->sendRequest($sessionId, $this->buildRequestBody('blocklist-update'));
        } catch (TransmissionException $e) {
            if ('gotNewBlocklist: http error 404: Not Found' === $e->getResult()) {
                throw new BlocklistNotFoundException();
            } else {
                throw $e;
            }
        }

        return $response['arguments']['blocklist-size'];
    }

    /**
     * @inheritdoc
     */
    public function portTest($sessionId)
    {
        $response = $this->sendRequest($sessionId, $this->buildRequestBody('port-test'));

        return $response['arguments']['port-is-open'];
    }

    /**
     * @inheritdoc
     */
    public function sessionClose($sessionId)
    {
        $this->sendRequest($sessionId, $this->buildRequestBody('session-close'));
    }

    /**
     * @inheritdoc
     */
    public function queueMoveTop($sessionId, TorrentIdList $ids)
    {
        $this->sendRequest($sessionId, $this->buildRequestBody('queue-move-top', ['ids' => $ids->getList()]));
    }

    /**
     * @inheritdoc
     */
    public function queueMoveDown($sessionId, TorrentIdList $ids)
    {
        $this->sendRequest($sessionId, $this->buildRequestBody('queue-move-down', ['ids' => $ids->getList()]));
    }

    /**
     * @inheritdoc
     */
    public function queueMoveUp($sessionId, TorrentIdList $ids)
    {
        $this->sendRequest($sessionId, $this->buildRequestBody('queue-move-up', ['ids' => $ids->getList()]));
    }

    /**
     * @inheritdoc
     */
    public function queueMoveBottom($sessionId, TorrentIdList $ids)
    {
        $this->sendRequest($sessionId, $this->buildRequestBody('queue-move-bottom', ['ids' => $ids->getList()]));
    }

    /**
     * @inheritdoc
     */
    public function freeSpace($sessionId, $path)
    {
        $response = $this->sendRequest($sessionId, $this->buildRequestBody('free-space', ['path' => $path]));

        return $response['arguments'];
    }

    /**
     * Returns the JSON representation of a request body.
     *
     * @param string $method
     * @param array $arguments
     * @return string
     */
    private function buildRequestBody($method, array $arguments = [])
    {
        $body = new \StdClass();
        $body->method = $method;

        if (!empty($arguments)) {
            $body->arguments = new \StdClass();

            foreach ($arguments as $argument => $value) {
                $body->arguments->$argument = $value;
            }
        }

        return json_encode($body, JSON_UNESCAPED_SLASHES);
    }

    /**
     * Sends the request and handles the common errors.
     *
     * @param string $sessionId
     * @param string $requestBody
     * @return array
     * @throws CSRFException
     * @throws DuplicateTorrentException
     * @throws TransmissionException
     */
    private function sendRequest($sessionId, $requestBody)
    {
        try {
            $this->log('debug', 'Request sent to the Transmission RPC API.', ['request' => $requestBody]);

            $response = $this
                ->httpClient
                ->request('POST', '', [
                    'body' => $requestBody,
                    'auth' => [$this->rpcUsername, $this->rpcPassword],
                    'headers' => [
                        'X-Transmission-Session-Id' => $sessionId
                    ]
                ]);
        } catch (ClientException $e) {
            if (409 === $e->getCode()) {
                $csrfException = new CSRFException('Invalid transmission session ID.', 0, $e);

                $csrfException->setSessionId(
                    $e->getResponse()->getHeader('X-Transmission-Session-Id')[0]
                );

                $this->log('info', 'Invalid Transmission session ID. A new ID has been generated.', [
                    'session_id' => $csrfException->getSessionId()
                ]);

                throw $csrfException;
            }

            throw $e;
        } catch (RequestException $e) {
            $this->log('error', 'The Transmission RPC API returned a 500 error.', ['exception' => $e]);

            throw new TransmissionException('Transmission request error.', 0, $e);
        }

        $responseBody = json_decode($response->getBody(), true);

        if ($responseBody['result'] !== 'success') {
            $e = new TransmissionException('The Transmission RPC API returned an error: ' . $responseBody['result']);
            $e->setResult($responseBody['result']);
            $e->setArguments($responseBody['arguments']);

            $this->log('error', 'The Transmission RPC API returned an error with this request.', [
                'request' => $requestBody,
                'response' => $responseBody['result'],
                'exception' => $e
            ]);

            throw $e;
        }

        if (isset($responseBody['arguments']['torrent-duplicate'])) {
            $torrentDuplicateData = $responseBody['arguments']['torrent-duplicate'];
            $e = new DuplicateTorrentException();
            $e->setTorrentId($torrentDuplicateData['id']);
            $e->setTorrentName($torrentDuplicateData['name']);
            $e->setTorrentHashString($torrentDuplicateData['hashString']);

            throw $e;
        }

        return $responseBody;
    }

    private function log($level, $message, array $context = [])
    {
        if (!is_null($this->logger)) {
            $this->logger->log($level, $message, $context);
        }
    }
}
