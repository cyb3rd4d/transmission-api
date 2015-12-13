<?php

namespace Martial\Transmission\Api\Tests;

use GuzzleHttp\Exception\ClientException;
use Martial\Transmission\API\RpcClient;
use Mockery as m;

class RpcClientTest extends \PHPUnit_Framework_TestCase
{
    const RPC_USERNAME = 'seeder';
    const RPC_PASSWORD = 'p@55w0rD';

    /**
     * @var RpcClient
     */
    private $rpcClient;

    /**
     * @var m\MockInterface
     */
    private $guzzle;

    /**
     * @var m\MockInterface
     */
    private $guzzleResponse;

    /**
     * @var string
     */
    private $sessionId;

    protected function setUp()
    {
        $this->guzzle = m::mock('\GuzzleHttp\Client');
        $this->guzzleResponse = m::mock('\Psr\Http\Message\ResponseInterface');
        $this->sessionId = uniqid();
        $this->rpcClient = new RpcClient($this->guzzle, self::RPC_USERNAME, self::RPC_PASSWORD);
    }

    public function testTorrentStartWithSuccess()
    {
        $ids = [42, 1337];
        $requestBody = '{"method":"torrent-start","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $this->setResponseBody('{"arguments":{},"result":"success"}');

        $this->rpcClient->torrentStart($this->sessionId, $ids);
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testTorrentStartShouldThrowAnExceptionWhenTheRequestFails()
    {
        $ids = [42, 1337];
        $requestBody = '{"method":"torrent-start","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\RequestException'));

        $this->rpcClient->torrentStart($this->sessionId, $ids);
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testTorrentStartShouldThrowAnExceptionWhenTheRpcApiReturnsAnError()
    {
        $ids = [42, 1337];
        $requestBody = '{"method":"torrent-start","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $this->setResponseBody('{"arguments":{},"result":"error"}');

        $this->rpcClient->torrentStart($this->sessionId, $ids);
    }

    /**
     * @expectedException \Martial\Transmission\API\CSRFException
     */
    public function testTorrentStartShouldThrowAnExceptionWithAnInvalidSessionId()
    {
        $ids = [42, 1337];
        $requestBody = '{"method":"torrent-start","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow($this->generateCSRFException());

        $this->rpcClient->torrentStart($this->sessionId, $ids);
    }

    /**
     * @return ClientException
     */
    private function generateCSRFException()
    {
        $request = m::mock('\Psr\Http\Message\RequestInterface');
        $response = m::mock('\Psr\Http\Message\ResponseInterface');

        $response
            ->shouldReceive('getStatusCode')
            ->once()
            ->andReturn(409);

        return new ClientException('', $request, $response);
    }

    /**
     * @param string $requestBody
     * @return m\Expectation
     */
    private function sendRequest($requestBody)
    {
        return $this
            ->guzzle
            ->shouldReceive('post')
            ->once()
            ->with('', [
                'body' => $requestBody,
                'auth' => [self::RPC_USERNAME, self::RPC_PASSWORD],
                'headers' => [
                    'X-Transmission-Session-Id' => $this->sessionId
                ]
            ]);
    }

    /**
     * @param string $responseBody
     */
    private function setResponseBody($responseBody)
    {
        $this
            ->guzzleResponse
            ->shouldReceive('getBody')
            ->once()
            ->andReturn($responseBody);
    }
}
