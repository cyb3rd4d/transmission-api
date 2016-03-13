<?php

namespace Martial\Transmission\Api\Tests;

use GuzzleHttp\Exception\ClientException;
use Martial\Transmission\API\CSRFException;
use Martial\Transmission\API\DuplicateTorrentException;
use Martial\Transmission\API\RpcClient;
use Martial\Transmission\API\TorrentIdList;
use Martial\Transmission\API\TransmissionException;
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
     * @var m\MockInterface
     */
    private $logger;

    /**
     * @var string
     */
    private $sessionId;

    /**
     * @var TorrentIdList
     */
    private $torrentIdList;

    protected function setUp()
    {
        $this->guzzle = m::mock('\GuzzleHttp\ClientInterface');
        $this->guzzleResponse = m::mock('\Psr\Http\Message\ResponseInterface');
        $this->logger = m::mock('\Psr\Log\LoggerInterface');
        $this->sessionId = uniqid();
        $this->torrentIdList = new TorrentIdList(self::TORRENT_IDS);
        $this->rpcClient = new RpcClient($this->guzzle, self::RPC_USERNAME, self::RPC_PASSWORD, $this->logger);
    }

    public function testTorrentStartWithSuccess()
    {
        $requestBody = '{"method":"torrent-start","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $this->setResponseBody('{"arguments":{},"result":"success"}');

        $this->rpcClient->torrentStart($this->sessionId, $this->torrentIdList);
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testTorrentStartShouldThrowAnExceptionWhenTheServerReturnsAnError500()
    {
        $requestBody = '{"method":"torrent-start","arguments":{"ids":[42,1337]}}';
        $requestException = m::mock('\GuzzleHttp\Exception\RequestException');
        $this->logRequestError($requestException);

        $this
            ->sendRequest($requestBody)
            ->andThrow($requestException);

        $this->rpcClient->torrentStart($this->sessionId, $this->torrentIdList);
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

        $this->rpcClient->torrentStart($this->sessionId, $this->torrentIdList);
    }

    public function testTorrentStartShouldThrowAnExceptionWithAnInvalidSessionId()
    {
        $requestBody = '{"method":"torrent-start","arguments":{"ids":[42,1337]}}';
        $invalidSessionId = uniqid();

        $this
            ->sendRequest($requestBody, $invalidSessionId)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->torrentStart($invalidSessionId, $this->torrentIdList);
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

        $this->rpcClient->torrentStart($this->sessionId, $this->torrentIdList);
    }

    public function testTorrentStartNowWithSuccess()
    {
        $requestBody = '{"method":"torrent-start-now","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $this->setResponseBody('{"arguments":{},"result":"success"}');

        $this->rpcClient->torrentStartNow($this->sessionId, $this->torrentIdList);
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testTorrentStartNowShouldThrowAnExceptionWhenTheServerReturnsAnError500()
    {
        $requestBody = '{"method":"torrent-start-now","arguments":{"ids":[42,1337]}}';
        $requestException = m::mock('\GuzzleHttp\Exception\RequestException');
        $this->logRequestError($requestException);

        $this
            ->sendRequest($requestBody)
            ->andThrow($requestException);

        $this->rpcClient->torrentStartNow($this->sessionId, $this->torrentIdList);
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

        $this->rpcClient->torrentStartNow($this->sessionId, $this->torrentIdList);
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

        $this->rpcClient->torrentStartNow($this->sessionId, $this->torrentIdList);
    }

    public function testTorrentStartNowShouldThrowAnExceptionWithAnInvalidSessionId()
    {
        $requestBody = '{"method":"torrent-start-now","arguments":{"ids":[42,1337]}}';
        $invalidSessionId = uniqid();

        $this
            ->sendRequest($requestBody, $invalidSessionId)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->torrentStartNow($invalidSessionId, $this->torrentIdList);
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

        $this->rpcClient->torrentStop($this->sessionId, $this->torrentIdList);
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testTorrentStopShouldThrowAnExceptionWhenTheServerReturnsAnError500()
    {
        $requestBody = '{"method":"torrent-stop","arguments":{"ids":[42,1337]}}';
        $requestException = m::mock('\GuzzleHttp\Exception\RequestException');
        $this->logRequestError($requestException);

        $this
            ->sendRequest($requestBody)
            ->andThrow($requestException);

        $this->rpcClient->torrentStop($this->sessionId, $this->torrentIdList);
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

        $this->rpcClient->torrentStop($this->sessionId, $this->torrentIdList);
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

        $this->rpcClient->torrentStop($this->sessionId, $this->torrentIdList);
    }

    public function testTorrentStopShouldThrowAnExceptionWithAnInvalidSessionId()
    {
        $requestBody = '{"method":"torrent-stop","arguments":{"ids":[42,1337]}}';
        $invalidSessionId = uniqid();

        $this
            ->sendRequest($requestBody, $invalidSessionId)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->torrentStop($invalidSessionId, $this->torrentIdList);
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

        $this->rpcClient->torrentVerify($this->sessionId, $this->torrentIdList);
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testTorrentVerifyShouldThrowAnExceptionWhenTheServerReturnsAnError500()
    {
        $requestBody = '{"method":"torrent-verify","arguments":{"ids":[42,1337]}}';
        $requestException = m::mock('\GuzzleHttp\Exception\RequestException');
        $this->logRequestError($requestException);

        $this
            ->sendRequest($requestBody)
            ->andThrow($requestException);

        $this->rpcClient->torrentVerify($this->sessionId, $this->torrentIdList);
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

        $this->rpcClient->torrentVerify($this->sessionId, $this->torrentIdList);
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

        $this->rpcClient->torrentVerify($this->sessionId, $this->torrentIdList);
    }

    public function testTorrentVerifyShouldThrowAnExceptionWithAnInvalidSessionId()
    {
        $requestBody = '{"method":"torrent-verify","arguments":{"ids":[42,1337]}}';
        $invalidSessionId = uniqid();

        $this
            ->sendRequest($requestBody, $invalidSessionId)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->torrentVerify($invalidSessionId, $this->torrentIdList);
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

        $this->rpcClient->torrentReannounce($this->sessionId, $this->torrentIdList);
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testTorrentReannounceShouldThrowAnExceptionWhenTheServerReturnsAnError500()
    {
        $requestBody = '{"method":"torrent-reannounce","arguments":{"ids":[42,1337]}}';
        $requestException = m::mock('\GuzzleHttp\Exception\RequestException');
        $this->logRequestError($requestException);

        $this
            ->sendRequest($requestBody)
            ->andThrow($requestException);

        $this->rpcClient->torrentReannounce($this->sessionId, $this->torrentIdList);
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

        $this->rpcClient->torrentReannounce($this->sessionId, $this->torrentIdList);
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

        $this->rpcClient->torrentReannounce($this->sessionId, $this->torrentIdList);
    }

    public function testTorrentReannounceShouldThrowAnExceptionWithAnInvalidSessionId()
    {
        $requestBody = '{"method":"torrent-reannounce","arguments":{"ids":[42,1337]}}';
        $invalidSessionId = uniqid();

        $this
            ->sendRequest($requestBody, $invalidSessionId)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->torrentReannounce($invalidSessionId, $this->torrentIdList);
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

        $this->rpcClient->torrentSet($this->sessionId, $this->torrentIdList, $arguments);
    }


    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testTorrentSetShouldThrowAnExceptionWhenTheServerReturnsAnError500()
    {
        $arguments = ['downloadLimit' => 200, 'peer-limit' => 10];
        $requestBody = '{"method":"torrent-set","arguments":{"ids":[42,1337],"downloadLimit":200,"peer-limit":10}}';
        $requestException = m::mock('\GuzzleHttp\Exception\RequestException');
        $this->logRequestError($requestException);

        $this
            ->sendRequest($requestBody)
            ->andThrow($requestException);

        $this->rpcClient->torrentSet($this->sessionId, $this->torrentIdList, $arguments);
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

        $this->rpcClient->torrentSet($this->sessionId, $this->torrentIdList, $arguments);
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

        $this->rpcClient->torrentSet($this->sessionId, $this->torrentIdList, $arguments);
    }

    public function testTorrentSetShouldThrowAnExceptionWithAnInvalidSessionId()
    {
        $arguments = ['downloadLimit' => 200, 'peer-limit' => 10];
        $requestBody = '{"method":"torrent-set","arguments":{"ids":[42,1337],"downloadLimit":200,"peer-limit":10}}';
        $invalidSessionId = uniqid();

        $this
            ->sendRequest($requestBody, $invalidSessionId)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->torrentSet($invalidSessionId, $this->torrentIdList, $arguments);
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
        ], $this->rpcClient->torrentGet($this->sessionId, $this->torrentIdList, $fields));
    }

    public function testTorrentGetAllWithSuccess()
    {
        $fields = ['name', 'totalSize'];
        $requestBody = '{"method":"torrent-get","arguments":{"fields":["name","totalSize"]}}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $this->setResponseBody(
            '{"arguments":{"torrents":[{"name":"Fedora","totalSize":12345}]},"result":"success"}'
        );

        $this->assertSame([
            ['name' => 'Fedora', 'totalSize' => 12345]
        ], $this->rpcClient->torrentGet($this->sessionId, new TorrentIdList([]), $fields));
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testTorrentGetShouldThrowAnExceptionWhenTheServerReturnsAnError500()
    {
        $fields = ['name', 'totalSize'];
        $requestBody = '{"method":"torrent-get","arguments":{"ids":[42,1337],"fields":["name","totalSize"]}}';
        $requestException = m::mock('\GuzzleHttp\Exception\RequestException');
        $this->logRequestError($requestException);

        $this
            ->sendRequest($requestBody)
            ->andThrow($requestException);

        $this->rpcClient->torrentGet($this->sessionId, $this->torrentIdList, $fields);
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

        $this->rpcClient->torrentGet($this->sessionId, $this->torrentIdList, $fields);
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

        $this->rpcClient->torrentGet($this->sessionId, $this->torrentIdList, $fields);
    }

    public function testTorrentGetShouldThrowAnExceptionWithAnInvalidSessionId()
    {
        $fields = ['creator', 'totalSize'];
        $requestBody = '{"method":"torrent-get","arguments":{"ids":[42,1337],"fields":["creator","totalSize"]}}';
        $invalidSessionId = uniqid();

        $this
            ->sendRequest($requestBody, $invalidSessionId)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->torrentGet($invalidSessionId, $this->torrentIdList, $fields);
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
            '{"arguments":{"torrent-added":{"id":42,"name":"Fedora.iso","hashString":"' . $hashString . '"}},"result":"success"}'
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
        $requestException = m::mock('\GuzzleHttp\Exception\RequestException');
        $this->logRequestError($requestException);

        $this
            ->sendRequest($requestBody)
            ->andThrow($requestException);

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
        $invalidSessionId = uniqid();

        $this
            ->sendRequest($requestBody, $invalidSessionId)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->torrentAdd($invalidSessionId, $arguments);
        } catch (CSRFException $e) {
            $this->assertSame($this->sessionId, $e->getSessionId());
        }
    }

    public function testTorrentAddShouldThrowAnExceptionWithDuplicateTorrent()
    {
        $success = false;
        $arguments = ['filename' => '/path/to/Fedora.torrent'];
        $requestBody = '{"method":"torrent-add","arguments":{"filename":"/path/to/Fedora.torrent"}}';
        $hashString = md5('Fedora.iso');
        $torrentId = 42;
        $torrentName = 'Fedora.iso';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $this->setResponseBody(sprintf(
            '{"arguments":{"torrent-duplicate":{"id":%d,"name":"%s","hashString":"%s"}},"result":"success"}',
            $torrentId,
            $torrentName,
            $hashString
        ));

        try {
            $this->rpcClient->torrentAdd($this->sessionId, $arguments);
        } catch (DuplicateTorrentException $e) {
            $this->assertSame($torrentId, $e->getTorrentId());
            $this->assertSame($torrentName, $e->getTorrentName());
            $this->assertSame($hashString, $e->getTorrentHashString());
            $success = true;
        }

        if (!$success) {
            $this->fail('DuplicateTorrentException was not thrown.');
        }
    }

    public function testTorrentRemoveWithLocalDataWithSuccess()
    {
        $requestBody = '{"method":"torrent-remove","arguments":{"ids":[42,1337],"delete-local-data":true}}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $this->setResponseBody('{"arguments":{},"result":"success"}');

        $this->rpcClient->torrentRemove($this->sessionId, $this->torrentIdList, true);
    }

    public function testTorrentRemoveWithSuccess()
    {
        $requestBody = '{"method":"torrent-remove","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $this->setResponseBody('{"arguments":{},"result":"success"}');

        $this->rpcClient->torrentRemove($this->sessionId, $this->torrentIdList);
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testTorrentRemoveShouldThrowAnExceptionWhenTheServerReturnsAnError500()
    {
        $requestBody = '{"method":"torrent-remove","arguments":{"ids":[42,1337]}}';
        $requestException = m::mock('\GuzzleHttp\Exception\RequestException');
        $this->logRequestError($requestException);

        $this
            ->sendRequest($requestBody)
            ->andThrow($requestException);

        $this->rpcClient->torrentRemove($this->sessionId, $this->torrentIdList);
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

        $this->rpcClient->torrentRemove($this->sessionId, $this->torrentIdList);
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

        $this->rpcClient->torrentRemove($this->sessionId, $this->torrentIdList);
    }

    public function testTorrentRemoveShouldThrowAnExceptionWithAnInvalidSessionId()
    {
        $requestBody = '{"method":"torrent-remove","arguments":{"ids":[42,1337]}}';
        $invalidSessionId = uniqid();

        $this
            ->sendRequest($requestBody, $invalidSessionId)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->torrentRemove($invalidSessionId, $this->torrentIdList);
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

        $this->rpcClient->torrentSetLocation($this->sessionId, $this->torrentIdList, $location);
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

        $this->rpcClient->torrentSetLocation($this->sessionId, $this->torrentIdList, $location, true);
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testTorrentSetLocationShouldThrowAnExceptionWhenTheServerReturnsAnError500()
    {
        $location = '/path/to/file';
        $requestBody = '{"method":"torrent-set-location","arguments":{"ids":[42,1337],"location":"';
        $requestBody .= $location . '","move":false}}';
        $requestException = m::mock('\GuzzleHttp\Exception\RequestException');
        $this->logRequestError($requestException);

        $this
            ->sendRequest($requestBody)
            ->andThrow($requestException);

        $this->rpcClient->torrentSetLocation($this->sessionId, $this->torrentIdList, $location);
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

        $this->rpcClient->torrentSetLocation($this->sessionId, $this->torrentIdList, $location);
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

        $this->rpcClient->torrentSetLocation($this->sessionId, $this->torrentIdList, $location);
    }

    public function testTorrentSetLocationShouldThrowAnExceptionWithAnInvalidSessionId()
    {
        $location = '/path/to/file';
        $requestBody = '{"method":"torrent-set-location","arguments":{"ids":[42,1337],"location":"';
        $requestBody .= $location . '","move":false}}';
        $invalidSessionId = uniqid();

        $this
            ->sendRequest($requestBody, $invalidSessionId)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->torrentSetLocation($invalidSessionId, $this->torrentIdList, $location);
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
        $requestException = m::mock('\GuzzleHttp\Exception\RequestException');
        $this->logRequestError($requestException);

        $requestBody = sprintf(
            '{"method":"torrent-rename-path","arguments":{"ids":[42],"path":"%s","name":"%s"}}',
            $oldPath,
            $newPath
        );

        $this
            ->sendRequest($requestBody)
            ->andThrow($requestException);

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
        $invalidSessionId = uniqid();

        $requestBody = sprintf(
            '{"method":"torrent-rename-path","arguments":{"ids":[42],"path":"%s","name":"%s"}}',
            $oldPath,
            $newPath
        );

        $this
            ->sendRequest($requestBody, $invalidSessionId)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->torrentRenamePath($invalidSessionId, 42, $oldPath, $newPath);
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
        $requestException = m::mock('\GuzzleHttp\Exception\RequestException');
        $this->logRequestError($requestException);

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
            ->andThrow($requestException);

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
        $invalidSessionId = uniqid();

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
            ->sendRequest($requestBody, $invalidSessionId)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->sessionSet($invalidSessionId, $sessionArgs);
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
        $requestException = m::mock('\GuzzleHttp\Exception\RequestException');
        $this->logRequestError($requestException);

        $this
            ->sendRequest($requestBody)
            ->andThrow($requestException);

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
        $invalidSessionId = uniqid();

        $this
            ->sendRequest($requestBody, $invalidSessionId)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->sessionGet($invalidSessionId);
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
        $requestException = m::mock('\GuzzleHttp\Exception\RequestException');
        $this->logRequestError($requestException);

        $this
            ->sendRequest($requestBody)
            ->andThrow($requestException);

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
        $invalidSessionId = uniqid();

        $this
            ->sendRequest($requestBody, $invalidSessionId)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->sessionStats($invalidSessionId);
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
        $requestException = m::mock('\GuzzleHttp\Exception\RequestException');
        $this->logRequestError($requestException);

        $this
            ->sendRequest($requestBody)
            ->andThrow($requestException);

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
        $invalidSessionId = uniqid();

        $this
            ->sendRequest($requestBody, $invalidSessionId)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->blocklistUpdate($invalidSessionId);
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
        $requestException = m::mock('\GuzzleHttp\Exception\RequestException');
        $this->logRequestError($requestException);

        $this
            ->sendRequest($requestBody)
            ->andThrow($requestException);

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
        $invalidSessionId = uniqid();

        $this
            ->sendRequest($requestBody, $invalidSessionId)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->portTest($invalidSessionId);
        } catch (CSRFException $e) {
            $this->assertSame($this->sessionId, $e->getSessionId());
        }
    }

    public function testSessionCloseWithSuccess()
    {
        $requestBody = '{"method":"session-close"}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $jsonResponse = '{"arguments":{},"result":"success"}';
        $this->setResponseBody($jsonResponse);
        $this->rpcClient->sessionClose($this->sessionId);
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testSessionCloseShouldThrowAnExceptionWhenTheServerReturnsAnError500()
    {
        $requestBody = '{"method":"session-close"}';
        $requestException = m::mock('\GuzzleHttp\Exception\RequestException');
        $this->logRequestError($requestException);

        $this
            ->sendRequest($requestBody)
            ->andThrow($requestException);

        $this->rpcClient->sessionClose($this->sessionId);
    }

    /**
     * @expectedException \GuzzleHttp\Exception\ClientException
     */
    public function testSessionCloseShouldThrowAnExceptionWhenTheRequestFails()
    {
        $requestBody = '{"method":"session-close"}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\ClientException'));

        $this->rpcClient->sessionClose($this->sessionId);
    }

    public function testSessionCloseShouldThrowAnExceptionWithAnInvalidSessionId()
    {
        $requestBody = '{"method":"session-close"}';
        $invalidSessionId = uniqid();

        $this
            ->sendRequest($requestBody, $invalidSessionId)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->sessionClose($invalidSessionId);
        } catch (CSRFException $e) {
            $this->assertSame($this->sessionId, $e->getSessionId());
        }
    }

    public function testQueueMoveTopWithSuccess()
    {
        $requestBody = '{"method":"queue-move-top","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $jsonResponse = '{"arguments":{},"result":"success"}';
        $this->setResponseBody($jsonResponse);
        $this->rpcClient->queueMoveTop($this->sessionId, $this->torrentIdList);
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testQueueMoveTopShouldThrowAnExceptionWhenTheServerReturnsAnError500()
    {
        $requestBody = '{"method":"queue-move-top","arguments":{"ids":[42,1337]}}';
        $requestException = m::mock('\GuzzleHttp\Exception\RequestException');
        $this->logRequestError($requestException);

        $this
            ->sendRequest($requestBody)
            ->andThrow($requestException);

        $this->rpcClient->queueMoveTop($this->sessionId, $this->torrentIdList);
    }

    /**
     * @expectedException \GuzzleHttp\Exception\ClientException
     */
    public function testQueueMoveTopShouldThrowAnExceptionWhenTheRequestFails()
    {
        $requestBody = '{"method":"queue-move-top","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\ClientException'));

        $this->rpcClient->queueMoveTop($this->sessionId, $this->torrentIdList);
    }

    public function testQueueMoveTopShouldThrowAnExceptionWithAnInvalidSessionId()
    {
        $requestBody = '{"method":"queue-move-top","arguments":{"ids":[42,1337]}}';
        $invalidSessionId = uniqid();

        $this
            ->sendRequest($requestBody, $invalidSessionId)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->queueMoveTop($invalidSessionId, $this->torrentIdList);
        } catch (CSRFException $e) {
            $this->assertSame($this->sessionId, $e->getSessionId());
        }
    }

    public function testQueueMoveDownWithSuccess()
    {
        $requestBody = '{"method":"queue-move-down","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $jsonResponse = '{"arguments":{},"result":"success"}';
        $this->setResponseBody($jsonResponse);
        $this->rpcClient->queueMoveDown($this->sessionId, $this->torrentIdList);
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testQueueMoveDownShouldThrowAnExceptionWhenTheServerReturnsAnError500()
    {
        $requestBody = '{"method":"queue-move-down","arguments":{"ids":[42,1337]}}';
        $requestException = m::mock('\GuzzleHttp\Exception\RequestException');
        $this->logRequestError($requestException);

        $this
            ->sendRequest($requestBody)
            ->andThrow($requestException);

        $this->rpcClient->queueMoveDown($this->sessionId, $this->torrentIdList);
    }

    /**
     * @expectedException \GuzzleHttp\Exception\ClientException
     */
    public function testQueueMoveDownShouldThrowAnExceptionWhenTheRequestFails()
    {
        $requestBody = '{"method":"queue-move-down","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\ClientException'));

        $this->rpcClient->queueMoveDown($this->sessionId, $this->torrentIdList);
    }

    public function testQueueMoveDownShouldThrowAnExceptionWithAnInvalidSessionId()
    {
        $requestBody = '{"method":"queue-move-down","arguments":{"ids":[42,1337]}}';
        $invalidSessionId = uniqid();

        $this
            ->sendRequest($requestBody, $invalidSessionId)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->queueMoveDown($invalidSessionId, $this->torrentIdList);
        } catch (CSRFException $e) {
            $this->assertSame($this->sessionId, $e->getSessionId());
        }
    }

    public function testQueueMoveUpWithSuccess()
    {
        $requestBody = '{"method":"queue-move-up","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $jsonResponse = '{"arguments":{},"result":"success"}';
        $this->setResponseBody($jsonResponse);
        $this->rpcClient->queueMoveUp($this->sessionId, $this->torrentIdList);
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testQueueMoveUpShouldThrowAnExceptionWhenTheServerReturnsAnError500()
    {
        $requestBody = '{"method":"queue-move-up","arguments":{"ids":[42,1337]}}';
        $requestException = m::mock('\GuzzleHttp\Exception\RequestException');
        $this->logRequestError($requestException);

        $this
            ->sendRequest($requestBody)
            ->andThrow($requestException);

        $this->rpcClient->queueMoveUp($this->sessionId, $this->torrentIdList);
    }

    /**
     * @expectedException \GuzzleHttp\Exception\ClientException
     */
    public function testQueueMoveUpShouldThrowAnExceptionWhenTheRequestFails()
    {
        $requestBody = '{"method":"queue-move-up","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\ClientException'));

        $this->rpcClient->queueMoveUp($this->sessionId, $this->torrentIdList);
    }

    public function testQueueMoveUpShouldThrowAnExceptionWithAnInvalidSessionId()
    {
        $requestBody = '{"method":"queue-move-up","arguments":{"ids":[42,1337]}}';
        $invalidSessionId = uniqid();

        $this
            ->sendRequest($requestBody, $invalidSessionId)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->queueMoveUp($invalidSessionId, $this->torrentIdList);
        } catch (CSRFException $e) {
            $this->assertSame($this->sessionId, $e->getSessionId());
        }
    }

    public function testQueueMoveBottomWithSuccess()
    {
        $requestBody = '{"method":"queue-move-bottom","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $jsonResponse = '{"arguments":{},"result":"success"}';
        $this->setResponseBody($jsonResponse);
        $this->rpcClient->queueMoveBottom($this->sessionId, $this->torrentIdList);
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testQueueMoveBottomShouldThrowAnExceptionWhenTheServerReturnsAnError500()
    {
        $requestBody = '{"method":"queue-move-bottom","arguments":{"ids":[42,1337]}}';
        $requestException = m::mock('\GuzzleHttp\Exception\RequestException');
        $this->logRequestError($requestException);

        $this
            ->sendRequest($requestBody)
            ->andThrow($requestException);

        $this->rpcClient->queueMoveBottom($this->sessionId, $this->torrentIdList);
    }

    /**
     * @expectedException \GuzzleHttp\Exception\ClientException
     */
    public function testQueueMoveBottomShouldThrowAnExceptionWhenTheRequestFails()
    {
        $requestBody = '{"method":"queue-move-bottom","arguments":{"ids":[42,1337]}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\ClientException'));

        $this->rpcClient->queueMoveBottom($this->sessionId, $this->torrentIdList);
    }

    public function testQueueMoveBottomShouldThrowAnExceptionWithAnInvalidSessionId()
    {
        $requestBody = '{"method":"queue-move-bottom","arguments":{"ids":[42,1337]}}';
        $invalidSessionId = uniqid();

        $this
            ->sendRequest($requestBody, $invalidSessionId)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->queueMoveBottom($invalidSessionId, $this->torrentIdList);
        } catch (CSRFException $e) {
            $this->assertSame($this->sessionId, $e->getSessionId());
        }
    }

    public function testFreeSpaceWithSuccess()
    {
        $requestBody = '{"method":"free-space","arguments":{"path":"/var/lib/transmission-daemon/downloads"}}';
        $path = '/var/lib/transmission-daemon/downloads';
        $size = 37548523520;

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $jsonResponse = sprintf(
            '{"arguments":{"path":"%s","size-bytes":%d},"result":"success"}',
            $path,
            $size
        );

        $this->setResponseBody($jsonResponse);
        $result = $this->rpcClient->freeSpace($this->sessionId, $path);
        $this->assertInternalType('array', $result);
        $this->assertSame($path, $result['path']);
        $this->assertSame($size, $result['size-bytes']);
    }

    public function testFreeSpaceShouldThrowAnExceptionWhenThePathIsInvalid()
    {
        $requestBody = '{"method":"free-space","arguments":{"path":"/invalid/path"}}';

        $this
            ->sendRequest($requestBody)
            ->andReturn($this->guzzleResponse);

        $jsonResponse = '{"arguments":{"path":"/invalid/path","size-bytes":-1},"result":"No such file or directory"}';
        $this->setResponseBody($jsonResponse);

        try {
            $success = false;
            $this->rpcClient->freeSpace($this->sessionId, '/invalid/path');
        } catch (TransmissionException $e) {
            $this->assertSame('No such file or directory', $e->getResult());
            $success = true;
        }

        if (!$success) {
            $this->fail('An invalid path should throw an exception.');
        }
    }

    /**
     * @expectedException \Martial\Transmission\API\TransmissionException
     */
    public function testFreeSpaceShouldThrowAnExceptionWhenTheServerReturnsAnError500()
    {
        $requestBody = '{"method":"free-space","arguments":{"path":"/var/lib/transmission-daemon/downloads"}}';
        $requestException = m::mock('\GuzzleHttp\Exception\RequestException');
        $this->logRequestError($requestException);

        $this
            ->sendRequest($requestBody)
            ->andThrow($requestException);

        $this->rpcClient->freeSpace($this->sessionId, '/var/lib/transmission-daemon/downloads');
    }

    /**
     * @expectedException \GuzzleHttp\Exception\ClientException
     */
    public function testFreeSpaceShouldThrowAnExceptionWhenTheRequestFails()
    {
        $requestBody = '{"method":"free-space","arguments":{"path":"/var/lib/transmission-daemon/downloads"}}';

        $this
            ->sendRequest($requestBody)
            ->andThrow(m::mock('\GuzzleHttp\Exception\ClientException'));

        $this->rpcClient->freeSpace($this->sessionId, '/var/lib/transmission-daemon/downloads');
    }

    public function testFreeSpaceShouldThrowAnExceptionWithAnInvalidSessionId()
    {
        $requestBody = '{"method":"free-space","arguments":{"path":"/var/lib/transmission-daemon/downloads"}}';
        $invalidSessionId = uniqid();

        $this
            ->sendRequest($requestBody, $invalidSessionId)
            ->andThrow($this->generateCSRFException());

        try {
            $this->rpcClient->freeSpace($invalidSessionId, '/var/lib/transmission-daemon/downloads');
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
        $response = m::mock('\Psr\Http\Message\MessageInterface, \Psr\Http\Message\ResponseInterface');

        $response
            ->shouldReceive('getStatusCode')
            ->once()
            ->andReturn(409);

        $response
            ->shouldReceive('getHeader')
            ->once()
            ->andReturn([$this->sessionId]);

        $this
            ->logger
            ->shouldReceive('info')
            ->once()
            ->withArgs(['Invalid Transmission session ID. A new ID has been generated.', [
                'session_id' => $this->sessionId
            ]]);

        return new ClientException('', $request, $response);
    }

    /**
     * @param string $requestBody
     * @param string $sessionId
     * @return m\Expectation
     */
    private function sendRequest($requestBody, $sessionId = '')
    {
        $this->debugRequest($requestBody);

        $sessionId = '' === $sessionId ? $this->sessionId : $sessionId;

        return $this
            ->guzzle
            ->shouldReceive('request')
            ->once()
            ->withArgs([
                'POST',
                '',
                [
                    'body' => $requestBody,
                    'auth' => [self::RPC_USERNAME, self::RPC_PASSWORD],
                    'headers' => ['X-Transmission-Session-Id' => $sessionId]
                ]
            ]);
    }

    /**
     * @param string $responseBody
     */
    private function setResponseBody($responseBody)
    {
        $responseToArray = json_decode($responseBody, true);

        if ($responseToArray['result'] !== 'success') {
            $this
                ->logger
                ->shouldReceive('error')
                ->once();
        }

        $this
            ->guzzleResponse
            ->shouldReceive('getBody')
            ->once()
            ->andReturn($responseBody);
    }

    /**
     * @param string $requestBody
     */
    private function debugRequest($requestBody)
    {
        $this
            ->logger
            ->shouldReceive('debug')
            ->once()
            ->withArgs([
                'Request sent to the Transmission RPC API.',
                ['request' => $requestBody]
            ]);
    }

    private function logRequestError($requestException)
    {
        $this
            ->logger
            ->shouldReceive('error')
            ->once()
            ->withArgs(['The Transmission RPC API returned a 500 error.', [
                'exception' => $requestException
            ]]);
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
