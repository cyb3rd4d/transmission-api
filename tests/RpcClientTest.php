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
    public function testTorrentStartShouldThrowAnExceptionWhenTheServerReturnsAnError500()
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

    /**
     * @expectedException \GuzzleHttp\Exception\ClientException
     */
    public function testTorrentStartShouldThrowAnExceptionWhenTheRequestFails()
    {
        $requestBody = '{"method":"torrent-start","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\ClientException'));

        $this->rpcClient->torrentStart($this->sessionId, self::TORRENT_IDS);
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
    public function testTorrentStartNowShouldThrowAnExceptionWhenTheServerReturnsAnError500()
    {
        $requestBody = '{"method":"torrent-start-now","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\RequestException'));

        $this->rpcClient->torrentStartNow($this->sessionId, self::TORRENT_IDS);
    }

    /**
     * @expectedException \GuzzleHttp\Exception\ClientException
     */
    public function testTorrentStartNowShouldThrowAnExceptionWhenTheRequestFails()
    {
        $requestBody = '{"method":"torrent-start-now","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\ClientException'));

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
    public function testTorrentStopShouldThrowAnExceptionWhenTheServerReturnsAnError500()
    {
        $requestBody = '{"method":"torrent-stop","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\RequestException'));

        $this->rpcClient->torrentStop($this->sessionId, self::TORRENT_IDS);
    }

    /**
     * @expectedException \GuzzleHttp\Exception\ClientException
     */
    public function testTorrentStopShouldThrowAnExceptionWhenTheRequestFails()
    {
        $requestBody = '{"method":"torrent-stop","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\ClientException'));

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
    public function testTorrentVerifyShouldThrowAnExceptionWhenTheServerReturnsAnError500()
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

    /**
     * @expectedException \GuzzleHttp\Exception\ClientException
     */
    public function testVerifyStopShouldThrowAnExceptionWhenTheRequestFails()
    {
        $requestBody = '{"method":"torrent-verify","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\ClientException'));

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
    public function testTorrentReannounceShouldThrowAnExceptionWhenTheServerReturnsAnError500()
    {
        $requestBody = '{"method":"torrent-reannounce","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\RequestException'));

        $this->rpcClient->torrentReannounce($this->sessionId, self::TORRENT_IDS);
    }

    /**
     * @expectedException \GuzzleHttp\Exception\ClientException
     */
    public function testTorrentReannounceShouldThrowAnExceptionWhenTheRequestFails()
    {
        $requestBody = '{"method":"torrent-reannounce","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\ClientException'));

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
    public function testTorrentSetShouldThrowAnExceptionWhenTheServerReturnsAnError500()
    {
        $arguments = ['downloadLimit' => 200, 'peer-limit' => 10];
        $requestBody = '{"method":"torrent-set","arguments":{"ids":[42,1337],"downloadLimit":200,"peer-limit":10}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\RequestException'));

        $this->rpcClient->torrentSet($this->sessionId, self::TORRENT_IDS, $arguments);
    }

    /**
     * @expectedException \GuzzleHttp\Exception\ClientException
     */
    public function testTorrentSetShouldThrowAnExceptionWhenTheRequestFails()
    {
        $arguments = ['downloadLimit' => 200, 'peer-limit' => 10];
        $requestBody = '{"method":"torrent-set","arguments":{"ids":[42,1337],"downloadLimit":200,"peer-limit":10}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\ClientException'));

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
    public function testTorrentGetShouldThrowAnExceptionWhenTheServerReturnsAnError500()
    {
        $fields = ['name', 'totalSize'];
        $requestBody = '{"method":"torrent-get","arguments":{"ids":[42,1337],"fields":["name","totalSize"]}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\RequestException'));

        $this->rpcClient->torrentGet($this->sessionId, self::TORRENT_IDS, $fields);
    }

    /**
     * @expectedException \GuzzleHttp\Exception\ClientException
     */
    public function testTorrentGetShouldThrowAnExceptionWhenTheRequestFails()
    {
        $fields = ['name', 'totalSize'];
        $requestBody = '{"method":"torrent-get","arguments":{"ids":[42,1337],"fields":["name","totalSize"]}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\ClientException'));

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
    public function testTorrentAddShouldThrowAnExceptionWhenTheServerReturnsAnError500()
    {
        $arguments = ['filename' => '/path/to/Fedora.torrent'];
        $requestBody = '{"method":"torrent-add","arguments":{"filename":"/path/to/Fedora.torrent"}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\RequestException'));

        $this->rpcClient->torrentAdd($this->sessionId, $arguments);
    }

    /**
     * @expectedException \GuzzleHttp\Exception\ClientException
     */
    public function testTorrentAddShouldThrowAnExceptionWhenTheRequestFails()
    {
        $arguments = ['filename' => '/path/to/Fedora.torrent'];
        $requestBody = '{"method":"torrent-add","arguments":{"filename":"/path/to/Fedora.torrent"}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\ClientException'));

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

    public function testTorrentRemoveWithSuccess()
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
    public function testTorrentRemoveShouldThrowAnExceptionWhenTheServerReturnsAnError500()
    {
        $requestBody = '{"method":"torrent-remove","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\RequestException'));

        $this->rpcClient->torrentRemove($this->sessionId, self::TORRENT_IDS);
    }

    /**
     * @expectedException \GuzzleHttp\Exception\ClientException
     */
    public function testTorrentRemoveShouldThrowAnExceptionWhenTheRequestFails()
    {
        $requestBody = '{"method":"torrent-remove","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\ClientException'));

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

    public function testTorrentSetLocationWithSuccess()
    {
        $location = '/path/to/file';
        $requestBody = '{"method":"torrent-set-location","arguments":{"ids":[42,1337],"location":"';
        $requestBody .= $location . '","move":false}}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $this->setResponseBody('{"arguments":{},"result":"success"}');

        $this->rpcClient->torrentSetLocation($this->sessionId, self::TORRENT_IDS, $location);
    }

    public function testTorrentSetLocationWithMoveWithSuccess()
    {
        $location = '/path/to/file';
        $requestBody = '{"method":"torrent-set-location","arguments":{"ids":[42,1337],"location":"';
        $requestBody .= $location . '","move":true}}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $this->setResponseBody('{"arguments":{},"result":"success"}');

        $this->rpcClient->torrentSetLocation($this->sessionId, self::TORRENT_IDS, $location, true);
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testTorrentSetLocationShouldThrowAnExceptionWhenTheServerReturnsAnError500()
    {
        $location = '/path/to/file';
        $requestBody = '{"method":"torrent-set-location","arguments":{"ids":[42,1337],"location":"';
        $requestBody .= $location . '","move":false}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\RequestException'));

        $this->rpcClient->torrentSetLocation($this->sessionId, self::TORRENT_IDS, $location);
    }

    /**
     * @expectedException \GuzzleHttp\Exception\ClientException
     */
    public function testTorrentSetLocationShouldThrowAnExceptionWhenTheRequestFails()
    {
        $location = '/path/to/file';
        $requestBody = '{"method":"torrent-set-location","arguments":{"ids":[42,1337],"location":"';
        $requestBody .= $location . '","move":false}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\ClientException'));

        $this->rpcClient->torrentSetLocation($this->sessionId, self::TORRENT_IDS, $location);
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testTorrentSetLocationShouldThrowAnExceptionWhenTheRpcApiReturnsAnError()
    {
        $location = '/path/to/file';
        $requestBody = '{"method":"torrent-set-location","arguments":{"ids":[42,1337],"location":"';
        $requestBody .= $location . '","move":false}}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $this->setResponseBody('{"arguments":{},"result":"error"}');

        $this->rpcClient->torrentSetLocation($this->sessionId, self::TORRENT_IDS, $location);
    }

    public function testTorrentSetLocationShouldThrowAnExceptionWithAnInvalidSessionId()
    {
        $location = '/path/to/file';
        $requestBody = '{"method":"torrent-set-location","arguments":{"ids":[42,1337],"location":"';
        $requestBody .= $location . '","move":false}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->torrentSetLocation($this->sessionId, self::TORRENT_IDS, $location);
        } catch (CSRFException $e) {
            $this->assertSame($this->sessionId, $e->getSessionId());
        }
    }

    public function testTorrentRenamePathWithSuccess()
    {
        $oldPath = 'torrent.iso';
        $newPath = 'new-torrent.iso';

        $requestBody = sprintf(
            '{"method":"torrent-rename-path","arguments":{"ids":[42],"path":"%s","name":"%s"}}',
            $oldPath,
            $newPath
        );

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $this->setResponseBody(sprintf(
            '{"arguments":{"id":42,"name":"%s","path":"%s"},"result":"success"}',
            $newPath,
            $oldPath
        ));

        $result = $this->rpcClient->torrentRenamePath($this->sessionId, 42, $oldPath, $newPath);
        $this->assertSame(['id' => 42, 'name' => $newPath, 'path' => $oldPath], $result);
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testTorrentRenamePathFailedBecauseOfInvalidPaths()
    {
        $oldPath = 'invalid-torrent.iso';
        $newPath = 'invalid-new-torrent.iso';

        $requestBody = sprintf(
            '{"method":"torrent-rename-path","arguments":{"ids":[42],"path":"%s","name":"%s"}}',
            $oldPath,
            $newPath
        );

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $this->setResponseBody(sprintf(
            '{"arguments":{"id":42,"name":"%s","path":"%s"},"result":"Invalid argument"}',
            $newPath,
            $oldPath
        ));

        $this->rpcClient->torrentRenamePath($this->sessionId, 42, $oldPath, $newPath);
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testTorrentRenamePathShouldThrowAnExceptionWhenTheServerReturnsAnError500()
    {
        $oldPath = 'torrent.iso';
        $newPath = 'new-torrent.iso';

        $requestBody = sprintf(
            '{"method":"torrent-rename-path","arguments":{"ids":[42],"path":"%s","name":"%s"}}',
            $oldPath,
            $newPath
        );

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\RequestException'));

        $this->rpcClient->torrentRenamePath($this->sessionId, 42, $oldPath, $newPath);
    }

    /**
     * @expectedException \GuzzleHttp\Exception\ClientException
     */
    public function testTorrentRenamePathShouldThrowAnExceptionWhenTheRequestFails()
    {
        $oldPath = 'torrent.iso';
        $newPath = 'new-torrent.iso';

        $requestBody = sprintf(
            '{"method":"torrent-rename-path","arguments":{"ids":[42],"path":"%s","name":"%s"}}',
            $oldPath,
            $newPath
        );

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\ClientException'));

        $this->rpcClient->torrentRenamePath($this->sessionId, 42, $oldPath, $newPath);
    }

    public function testTorrentRenamePathShouldThrowAnExceptionWithAnInvalidSessionId()
    {
        $oldPath = 'torrent.iso';
        $newPath = 'new-torrent.iso';

        $requestBody = sprintf(
            '{"method":"torrent-rename-path","arguments":{"ids":[42],"path":"%s","name":"%s"}}',
            $oldPath,
            $newPath
        );

        $this
            ->sendRequest($requestBody)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->torrentRenamePath($this->sessionId, 42, $oldPath, $newPath);
        } catch (CSRFException $e) {
            $this->assertSame($this->sessionId, $e->getSessionId());
        }
    }

    public function testSessionSetWithSuccess()
    {
        $downloadDir = '/path/to/download-dir';
        $peerLimitGlobal = 42;

        $sessionArgs = [
            'download-dir' => $downloadDir,
            'peer-limit-global' => $peerLimitGlobal,
        ];

        $requestBody = sprintf(
            '{"method":"session-set","arguments":{"download-dir":"%s","peer-limit-global":%d}}',
            $downloadDir,
            $peerLimitGlobal
        );

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $this->setResponseBody('{"arguments":{},"result":"success"}');

        $this->rpcClient->sessionSet($this->sessionId, $sessionArgs);
    }

    public function testSessionSetShouldThrowAnExceptionWithUnauthorizedArguments()
    {
        $invalidArguments = [
            'blocklist-size' => 42,
            'config-dir' => '/path/to/config',
            'rpc-version' => 42,
            'rpc-version-minimum' => 42,
            'version' => 42,
        ];

        $requestBody = '{"method":"session-set","arguments":{"<argument>":<value>}}';

        foreach ($invalidArguments as $argument => $value) {
            $argumentValue = is_string($value) ? '"' . $value . '"' : $value;
            $requestBody = str_replace([
                '<argument>',
                '<value>',
            ], [
                $argument,
                $argumentValue,
            ], $requestBody);

            try {
                $this->rpcClient->sessionSet($this->sessionId, [$argument => $value]);
            } catch (\Martial\Transmission\API\TransmissionException $e) {
                continue;
            }
        }
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testSessionSetShouldThrowAnExceptionWhenTheServerReturnsAnError500()
    {
        $downloadDir = '/path/to/download-dir';
        $peerLimitGlobal = 42;

        $sessionArgs = [
            'download-dir' => $downloadDir,
            'peer-limit-global' => $peerLimitGlobal,
        ];

        $requestBody = sprintf(
            '{"method":"session-set","arguments":{"download-dir":"%s","peer-limit-global":%d}}',
            $downloadDir,
            $peerLimitGlobal
        );

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\RequestException'));

        $this->rpcClient->sessionSet($this->sessionId, $sessionArgs);
    }

    /**
     * @expectedException \GuzzleHttp\Exception\ClientException
     */
    public function testSessionSetShouldThrowAnExceptionWhenTheRequestFails()
    {
        $downloadDir = '/path/to/download-dir';
        $peerLimitGlobal = 42;

        $sessionArgs = [
            'download-dir' => $downloadDir,
            'peer-limit-global' => $peerLimitGlobal,
        ];

        $requestBody = sprintf(
            '{"method":"session-set","arguments":{"download-dir":"%s","peer-limit-global":%d}}',
            $downloadDir,
            $peerLimitGlobal
        );

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\ClientException'));

        $this->rpcClient->sessionSet($this->sessionId, $sessionArgs);
    }

    public function testSessionSetShouldThrowAnExceptionWithAnInvalidSessionId()
    {
        $downloadDir = '/path/to/download-dir';
        $peerLimitGlobal = 42;

        $sessionArgs = [
            'download-dir' => $downloadDir,
            'peer-limit-global' => $peerLimitGlobal,
        ];

        $requestBody = sprintf(
            '{"method":"session-set","arguments":{"download-dir":"%s","peer-limit-global":%d}}',
            $downloadDir,
            $peerLimitGlobal
        );

        $this
            ->sendRequest($requestBody)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->sessionSet($this->sessionId, $sessionArgs);
        } catch (CSRFException $e) {
            $this->assertSame($this->sessionId, $e->getSessionId());
        }
    }

    public function testSessionGetWithSuccess()
    {
        $requestBody = '{"method":"session-get"}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $jsonResponse = $this->getSessionGetResponse();
        $responseToArray = json_decode($jsonResponse, true);
        $this->setResponseBody($jsonResponse);
        $result = $this->rpcClient->sessionGet($this->sessionId);
        $this->assertSame($responseToArray['arguments'], $result);
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testSessionGetShouldThrowAnExceptionWhenTheServerReturnsAnError500()
    {
        $requestBody = '{"method":"session-get"}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\RequestException'));

        $this->rpcClient->sessionGet($this->sessionId);
    }

    /**
     * @expectedException \GuzzleHttp\Exception\ClientException
     */
    public function testSessionGetShouldThrowAnExceptionWhenTheRequestFails()
    {
        $requestBody = '{"method":"session-get"}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\ClientException'));

        $this->rpcClient->sessionGet($this->sessionId);
    }

    public function testSessionGetShouldThrowAnExceptionWithAnInvalidSessionId()
    {
        $requestBody = '{"method":"session-get"}';

        $this
            ->sendRequest($requestBody)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->sessionGet($this->sessionId);
        } catch (CSRFException $e) {
            $this->assertSame($this->sessionId, $e->getSessionId());
        }
    }

    public function testSessionStatsWithSuccess()
    {
        $requestBody = '{"method":"session-stats"}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $jsonResponse = $this->getSessionStatsResponse();
        $responseToArray = json_decode($jsonResponse, true);
        $this->setResponseBody($jsonResponse);
        $result = $this->rpcClient->sessionStats($this->sessionId);
        $this->assertSame($responseToArray['arguments'], $result);
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testSessionStatsShouldThrowAnExceptionWhenTheServerReturnsAnError500()
    {
        $requestBody = '{"method":"session-stats"}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\RequestException'));

        $this->rpcClient->sessionStats($this->sessionId);
    }

    /**
     * @expectedException \GuzzleHttp\Exception\ClientException
     */
    public function testSessionStatsShouldThrowAnExceptionWhenTheRequestFails()
    {
        $requestBody = '{"method":"session-stats"}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\ClientException'));

        $this->rpcClient->sessionStats($this->sessionId);
    }

    public function testSessionStatsShouldThrowAnExceptionWithAnInvalidSessionId()
    {
        $requestBody = '{"method":"session-stats"}';

        $this
            ->sendRequest($requestBody)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->sessionStats($this->sessionId);
        } catch (CSRFException $e) {
            $this->assertSame($this->sessionId, $e->getSessionId());
        }
    }

    public function testBlocklistUpdateWithSuccess()
    {
        $requestBody = '{"method":"blocklist-update"}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $jsonResponse = '{"arguments":{"blocklist-size":393003},"result":"success"}';
        $this->setResponseBody($jsonResponse);
        $result = $this->rpcClient->blocklistUpdate($this->sessionId);
        $this->assertSame(393003, $result);
    }

    /**
     * @expectedException \Martial\Transmission\API\BlocklistNotFoundException
     */
    public function testBlocklistUpdateShouldThrowAnExceptionWhenTheBlocklistUrlWasNotFound()
    {
        $requestBody = '{"method":"blocklist-update"}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $jsonResponse = '{"arguments":{},"result":"gotNewBlocklist: http error 404: Not Found"}';
        $this->setResponseBody($jsonResponse);
        $this->rpcClient->blocklistUpdate($this->sessionId);
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testBlocklistUpdateShouldThrowAnExceptionWhenTheServerReturnsAnError500()
    {
        $requestBody = '{"method":"blocklist-update"}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\RequestException'));

        $this->rpcClient->blocklistUpdate($this->sessionId);
    }

    /**
     * @expectedException \GuzzleHttp\Exception\ClientException
     */
    public function testBlocklistUpdateShouldThrowAnExceptionWhenTheRequestFails()
    {
        $requestBody = '{"method":"blocklist-update"}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\ClientException'));

        $this->rpcClient->blocklistUpdate($this->sessionId);
    }

    public function testBlocklistUpdateShouldThrowAnExceptionWithAnInvalidSessionId()
    {
        $requestBody = '{"method":"blocklist-update"}';

        $this
            ->sendRequest($requestBody)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->blocklistUpdate($this->sessionId);
        } catch (CSRFException $e) {
            $this->assertSame($this->sessionId, $e->getSessionId());
        }
    }

    public function testPortTestReturnsTrueWithSuccess()
    {
        $requestBody = '{"method":"port-test"}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $jsonResponse = '{"arguments":{"port-is-open":true},"result":"success"}';
        $this->setResponseBody($jsonResponse);
        $this->assertTrue($this->rpcClient->portTest($this->sessionId));
    }

    public function testPortTestReturnsFalseWithSuccess()
    {
        $requestBody = '{"method":"port-test"}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $jsonResponse = '{"arguments":{"port-is-open":false},"result":"success"}';
        $this->setResponseBody($jsonResponse);
        $this->assertFalse($this->rpcClient->portTest($this->sessionId));
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testPortTestShouldThrowAnExceptionWhenTheServerReturnsAnError500()
    {
        $requestBody = '{"method":"port-test"}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\RequestException'));

        $this->rpcClient->portTest($this->sessionId);
    }

    /**
     * @expectedException \GuzzleHttp\Exception\ClientException
     */
    public function testPortTestShouldThrowAnExceptionWhenTheRequestFails()
    {
        $requestBody = '{"method":"port-test"}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\ClientException'));

        $this->rpcClient->portTest($this->sessionId);
    }

    public function testPortTestShouldThrowAnExceptionWithAnInvalidSessionId()
    {
        $requestBody = '{"method":"port-test"}';

        $this
            ->sendRequest($requestBody)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->portTest($this->sessionId);
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

    /**
     * @return string
     */
    private function getSessionGetResponse()
    {
        return <<<JSON
{"arguments":{"alt-speed-down":50,"alt-speed-enabled":false,"alt-speed-time-begin":540,"alt-speed-time-day":127,"alt-speed-time-enabled":false,"alt-speed-time-end":1020,"alt-speed-up":50,"blocklist-enabled":false,"blocklist-size":0,"blocklist-url":"http://www.example.com/blocklist","cache-size-mb":4,"config-dir":"/var/lib/transmission-daemon/info","dht-enabled":true,"download-dir":"/var/lib/transmission-daemon/downloads","download-dir-free-space":37738184704,"download-queue-enabled":true,"download-queue-size":5,"encryption":"preferred","idle-seeding-limit":30,"idle-seeding-limit-enabled":false,"incomplete-dir":"/home/debian-transmission/Downloads","incomplete-dir-enabled":false,"lpd-enabled":false,"peer-limit-global":200,"peer-limit-per-torrent":50,"peer-port":51413,"peer-port-random-on-start":false,"pex-enabled":true,"port-forwarding-enabled":false,"queue-stalled-enabled":true,"queue-stalled-minutes":30,"rename-partial-files":true,"rpc-version":15,"rpc-version-minimum":1,"script-torrent-done-enabled":false,"script-torrent-done-filename":"","seed-queue-enabled":false,"seed-queue-size":10,"seedRatioLimit":2,"seedRatioLimited":false,"speed-limit-down":100,"speed-limit-down-enabled":false,"speed-limit-up":100,"speed-limit-up-enabled":false,"start-added-torrents":true,"trash-original-torrent-files":false,"units":{"memory-bytes":1024,"memory-units":["KiB","MiB","GiB","TiB"],"size-bytes":1000,"size-units":["kB","MB","GB","TB"],"speed-bytes":1000,"speed-units":["kB/s","MB/s","GB/s","TB/s"]},"utp-enabled":true,"version":"2.82 (14160)"},"result":"success"}
JSON;

    }

    /**
     * @return string
     */
    private function getSessionStatsResponse()
    {
        return <<<JSON
{"arguments":{"activeTorrentCount":1,"cumulative-stats":{"downloadedBytes":684902784,"filesAdded":1,"secondsActive":22309,"sessionCount":1,"uploadedBytes":196764},"current-stats":{"downloadedBytes":684902784,"filesAdded":1,"secondsActive":22309,"sessionCount":1,"uploadedBytes":196764},"downloadSpeed":0,"pausedTorrentCount":0,"torrentCount":1,"uploadSpeed":0},"result":"success"}
JSON;

    }
}
