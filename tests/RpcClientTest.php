<?php

namespace Martial\Transmission\Api\Tests;

use GuzzleHttp\Exception\ClientException;
use Martial\Transmission\API\CSRFException;
use Martial\Transmission\API\RpcClient;
use Mockery as m;

class RpcClientTest extends \PHPUnit_Framework_TestCase
{
    const RPC_USERNAME = 'seeder';
    const RPC_PASSWORD = 'p@55w0rD';
    const TORRENT_IDS = [42, 1337];

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
        $requestBody = '{"method":"torrent-start","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $this->setResponseBody('{"arguments":{},"result":"success"}');

        $this->rpcClient->torrentStart($this->sessionId, self::TORRENT_IDS);
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testTorrentStartShouldThrowAnExceptionWhenTheRequestFails()
    {
        $requestBody = '{"method":"torrent-start","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\RequestException'));

        $this->rpcClient->torrentStart($this->sessionId, self::TORRENT_IDS);
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testTorrentStartShouldThrowAnExceptionWhenTheRpcApiReturnsAnError()
    {
        $requestBody = '{"method":"torrent-start","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $this->setResponseBody('{"arguments":{},"result":"error"}');

        $this->rpcClient->torrentStart($this->sessionId, self::TORRENT_IDS);
    }

    public function testTorrentStartShouldThrowAnExceptionWithAnInvalidSessionId()
    {
        $requestBody = '{"method":"torrent-start","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->torrentStart($this->sessionId, self::TORRENT_IDS);
        } catch (CSRFException $e) {
            $this->assertSame($this->sessionId, $e->getSessionId());
        }
    }

    public function testTorrentStartNowWithSuccess()
    {
        $requestBody = '{"method":"torrent-start-now","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $this->setResponseBody('{"arguments":{},"result":"success"}');

        $this->rpcClient->torrentStartNow($this->sessionId, self::TORRENT_IDS);
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testTorrentStartNowShouldThrowAnExceptionWhenTheRequestFails()
    {
        $requestBody = '{"method":"torrent-start-now","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\RequestException'));

        $this->rpcClient->torrentStartNow($this->sessionId, self::TORRENT_IDS);
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testTorrentStartNowShouldThrowAnExceptionWhenTheRpcApiReturnsAnError()
    {
        $requestBody = '{"method":"torrent-start-now","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $this->setResponseBody('{"arguments":{},"result":"error"}');

        $this->rpcClient->torrentStartNow($this->sessionId, self::TORRENT_IDS);
    }

    public function testTorrentStartNowShouldThrowAnExceptionWithAnInvalidSessionId()
    {
        $requestBody = '{"method":"torrent-start-now","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->torrentStartNow($this->sessionId, self::TORRENT_IDS);
        } catch (CSRFException $e) {
            $this->assertSame($this->sessionId, $e->getSessionId());
        }
    }

    public function testTorrentStopWithSuccess()
    {
        $requestBody = '{"method":"torrent-stop","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $this->setResponseBody('{"arguments":{},"result":"success"}');

        $this->rpcClient->torrentStop($this->sessionId, self::TORRENT_IDS);
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testTorrentStopShouldThrowAnExceptionWhenTheRequestFails()
    {
        $requestBody = '{"method":"torrent-stop","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\RequestException'));

        $this->rpcClient->torrentStop($this->sessionId, self::TORRENT_IDS);
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testTorrentStopShouldThrowAnExceptionWhenTheRpcApiReturnsAnError()
    {
        $requestBody = '{"method":"torrent-stop","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $this->setResponseBody('{"arguments":{},"result":"error"}');

        $this->rpcClient->torrentStop($this->sessionId, self::TORRENT_IDS);
    }

    public function testTorrentStopShouldThrowAnExceptionWithAnInvalidSessionId()
    {
        $requestBody = '{"method":"torrent-stop","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->torrentStop($this->sessionId, self::TORRENT_IDS);
        } catch (CSRFException $e) {
            $this->assertSame($this->sessionId, $e->getSessionId());
        }
    }

    public function testTorrentVerifyWithSuccess()
    {
        $requestBody = '{"method":"torrent-verify","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $this->setResponseBody('{"arguments":{},"result":"success"}');

        $this->rpcClient->torrentVerify($this->sessionId, self::TORRENT_IDS);
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testTorrentVerifyShouldThrowAnExceptionWhenTheRequestFails()
    {
        $requestBody = '{"method":"torrent-verify","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\RequestException'));

        $this->rpcClient->torrentVerify($this->sessionId, self::TORRENT_IDS);
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testTorrentVerifyShouldThrowAnExceptionWhenTheRpcApiReturnsAnError()
    {
        $requestBody = '{"method":"torrent-verify","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $this->setResponseBody('{"arguments":{},"result":"error"}');

        $this->rpcClient->torrentVerify($this->sessionId, self::TORRENT_IDS);
    }

    public function testTorrentVerifyShouldThrowAnExceptionWithAnInvalidSessionId()
    {
        $requestBody = '{"method":"torrent-verify","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->torrentVerify($this->sessionId, self::TORRENT_IDS);
        } catch (CSRFException $e) {
            $this->assertSame($this->sessionId, $e->getSessionId());
        }
    }

    public function testTorrentReannounceWithSuccess()
    {
        $requestBody = '{"method":"torrent-reannounce","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $this->setResponseBody('{"arguments":{},"result":"success"}');

        $this->rpcClient->torrentReannounce($this->sessionId, self::TORRENT_IDS);
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testTorrentReannounceShouldThrowAnExceptionWhenTheRequestFails()
    {
        $requestBody = '{"method":"torrent-reannounce","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\RequestException'));

        $this->rpcClient->torrentReannounce($this->sessionId, self::TORRENT_IDS);
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testTorrentReannounceShouldThrowAnExceptionWhenTheRpcApiReturnsAnError()
    {
        $requestBody = '{"method":"torrent-reannounce","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $this->setResponseBody('{"arguments":{},"result":"error"}');

        $this->rpcClient->torrentReannounce($this->sessionId, self::TORRENT_IDS);
    }

    public function testTorrentReannounceShouldThrowAnExceptionWithAnInvalidSessionId()
    {
        $requestBody = '{"method":"torrent-reannounce","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->torrentReannounce($this->sessionId, self::TORRENT_IDS);
        } catch (CSRFException $e) {
            $this->assertSame($this->sessionId, $e->getSessionId());
        }
    }

    public function testTorrentSetWithSuccess()
    {
        $arguments = ['downloadLimit' => 200, 'peer-limit' => 10];
        $requestBody = '{"method":"torrent-set","arguments":{"ids":[42,1337],"downloadLimit":200,"peer-limit":10}}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $this->setResponseBody('{"arguments":{},"result":"success"}');

        $this->rpcClient->torrentSet($this->sessionId, self::TORRENT_IDS, $arguments);
    }


    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testTorrentSetShouldThrowAnExceptionWhenTheRequestFails()
    {
        $arguments = ['downloadLimit' => 200, 'peer-limit' => 10];
        $requestBody = '{"method":"torrent-set","arguments":{"ids":[42,1337],"downloadLimit":200,"peer-limit":10}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\RequestException'));

        $this->rpcClient->torrentSet($this->sessionId, self::TORRENT_IDS, $arguments);
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testTorrentSetShouldThrowAnExceptionWhenTheRpcApiReturnsAnError()
    {
        $arguments = ['downloadLimit' => 200, 'peer-limit' => 10];
        $requestBody = '{"method":"torrent-set","arguments":{"ids":[42,1337],"downloadLimit":200,"peer-limit":10}}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $this->setResponseBody('{"arguments":{},"result":"error"}');

        $this->rpcClient->torrentSet($this->sessionId, self::TORRENT_IDS, $arguments);
    }

    public function testTorrentSetShouldThrowAnExceptionWithAnInvalidSessionId()
    {
        $arguments = ['downloadLimit' => 200, 'peer-limit' => 10];
        $requestBody = '{"method":"torrent-set","arguments":{"ids":[42,1337],"downloadLimit":200,"peer-limit":10}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->torrentSet($this->sessionId, self::TORRENT_IDS, $arguments);
        } catch (CSRFException $e) {
            $this->assertSame($this->sessionId, $e->getSessionId());
        }
    }

    public function testTorrentGetWithSuccess()
    {
        $fields = ['name', 'totalSize'];
        $requestBody = '{"method":"torrent-get","arguments":{"ids":[42,1337],"fields":["name","totalSize"]}}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $this->setResponseBody(
            '{"arguments":{"torrents":[{"name":"Fedora","totalSize":12345}]},"result":"success"}'
        );

        $this->assertSame([
            ['name' => 'Fedora', 'totalSize' => 12345]
        ], $this->rpcClient->torrentGet($this->sessionId, self::TORRENT_IDS, $fields));
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testTorrentGetShouldThrowAnExceptionWhenTheRequestFails()
    {
        $fields = ['name', 'totalSize'];
        $requestBody = '{"method":"torrent-get","arguments":{"ids":[42,1337],"fields":["name","totalSize"]}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\RequestException'));

        $this->rpcClient->torrentGet($this->sessionId, self::TORRENT_IDS, $fields);
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testTorrentGetShouldThrowAnExceptionWhenTheRpcApiReturnsAnError()
    {
        $fields = ['creator', 'totalSize'];
        $requestBody = '{"method":"torrent-get","arguments":{"ids":[42,1337],"fields":["creator","totalSize"]}}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $this->setResponseBody('{"arguments":{},"result":"error"}');

        $this->rpcClient->torrentGet($this->sessionId, self::TORRENT_IDS, $fields);
    }

    public function testTorrentGetShouldThrowAnExceptionWithAnInvalidSessionId()
    {
        $fields = ['creator', 'totalSize'];
        $requestBody = '{"method":"torrent-get","arguments":{"ids":[42,1337],"fields":["creator","totalSize"]}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->torrentGet($this->sessionId, self::TORRENT_IDS, $fields);
        } catch (CSRFException $e) {
            $this->assertSame($this->sessionId, $e->getSessionId());
        }
    }

    public function testTorrentAddWithSuccess()
    {
        $arguments = ['filename' => '/path/to/Fedora.torrent'];
        $requestBody = '{"method":"torrent-add","arguments":{"filename":"/path/to/Fedora.torrent"}}';
        $hashString = md5('Fedora.iso');

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $this->setResponseBody(
            '{"arguments":{"torrents":[{"id":42,"name":"Fedora.iso","hashString":"' . $hashString . '"}]},"result":"success"}'
        );

        $this->assertSame(
            ['id' => 42, 'name' => 'Fedora.iso', 'hashString' => $hashString],
            $this->rpcClient->torrentAdd($this->sessionId, $arguments)
        );
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testTorrentAddShouldThrowAnExceptionWhenTheRequestFails()
    {
        $arguments = ['filename' => '/path/to/Fedora.torrent'];
        $requestBody = '{"method":"torrent-add","arguments":{"filename":"/path/to/Fedora.torrent"}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\RequestException'));

        $this->rpcClient->torrentAdd($this->sessionId, $arguments);
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testTorrentAddShouldThrowAnExceptionWhenTheRpcApiReturnsAnError()
    {
        $arguments = ['filename' => '/path/to/Fedora.torrent'];
        $requestBody = '{"method":"torrent-add","arguments":{"filename":"/path/to/Fedora.torrent"}}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $this->setResponseBody('{"arguments":{},"result":"error"}');

        $this->rpcClient->torrentAdd($this->sessionId, $arguments);
    }

    /**
     * @expectedException \Martial\Transmission\API\MissingArgumentException
     */
    public function testTorrentAddShouldThrowAnExceptionWhenRequiredArgumentIsMissing()
    {
        $this->rpcClient->torrentAdd($this->sessionId, ['invalidArgument' => '']);
    }

    public function testTorrentAddShouldThrowAnExceptionWithAnInvalidSessionId()
    {
        $arguments = ['filename' => '/path/to/Fedora.torrent'];
        $requestBody = '{"method":"torrent-add","arguments":{"filename":"/path/to/Fedora.torrent"}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->torrentAdd($this->sessionId, $arguments);
        } catch (CSRFException $e) {
            $this->assertSame($this->sessionId, $e->getSessionId());
        }
    }

    public function testTorrentRemoveWithLocalDataWithSuccess()
    {
        $requestBody = '{"method":"torrent-remove","arguments":{"ids":[42,1337],"delete-local-data":true}}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $this->setResponseBody('{"arguments":{},"result":"success"}');

        $this->rpcClient->torrentRemove($this->sessionId, self::TORRENT_IDS, true);
    }

    public function testTorrentRemoveDataWithSuccess()
    {
        $requestBody = '{"method":"torrent-remove","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $this->setResponseBody('{"arguments":{},"result":"success"}');

        $this->rpcClient->torrentRemove($this->sessionId, self::TORRENT_IDS);
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testTorrentRemoveShouldThrowAnExceptionWhenTheRequestFails()
    {
        $requestBody = '{"method":"torrent-remove","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\RequestException'));

        $this->rpcClient->torrentRemove($this->sessionId, self::TORRENT_IDS);
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testTorrentRemoveShouldThrowAnExceptionWhenTheRpcApiReturnsAnError()
    {
        $requestBody = '{"method":"torrent-remove","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $this->setResponseBody('{"arguments":{},"result":"error"}');

        $this->rpcClient->torrentRemove($this->sessionId, self::TORRENT_IDS);
    }

    public function testTorrentRemoveShouldThrowAnExceptionWithAnInvalidSessionId()
    {
        $requestBody = '{"method":"torrent-remove","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->torrentRemove($this->sessionId, self::TORRENT_IDS);
        } catch (CSRFException $e) {
            $this->assertSame($this->sessionId, $e->getSessionId());
        }
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
