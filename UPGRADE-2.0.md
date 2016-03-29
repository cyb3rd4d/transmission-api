# UPGRADE FROM 1.x TO 2.0

## Torrent IDs

The array of torrent IDs provided as an argument of almost all the client's methods is replaced by a new class
\Martial\Transmission\API\TorrentIdList. This class has been created to ensure that the request to the
Transmission RPC API is well formatted.
The constructor of this class has one argument, your array of IDs.

Before:

```php
$api = new \Martial\Transmission\API\RpcClient($guzzle, $rpcUsername, $rpcPassword);
$sessionId = // Fetch a new session ID
$torrentIds = [42, 43, 44];
$api->torrentGet($sessionId, $torrentIds, [
    \Martial\Transmission\API\Argument\Torrent\Get::ID,
    \Martial\Transmission\API\Argument\Torrent\Get::NAME,
    \Martial\Transmission\API\Argument\Torrent\Get::STATUS
]);
```

Now:

```php
$api = new \Martial\Transmission\API\RpcClient($guzzle, $rpcUsername, $rpcPassword);
$sessionId = // Fetch a new session ID
$torrentIds = new \Martial\Transmission\API\TorrentIdList([42, 43, 44]);
$api->torrentGet($sessionId, $torrentIds, [
    \Martial\Transmission\API\Argument\Torrent\Get::ID,
    \Martial\Transmission\API\Argument\Torrent\Get::NAME,
    \Martial\Transmission\API\Argument\Torrent\Get::STATUS
]);
```

## Logger

The method \Martial\Transmission\API\TransmissionAPI::setLogger() has been removed because it violated the SRP
principle. The logger can now be injected as fourth argument of the client's controller.

Before:

```php
$api = new \Martial\Transmission\API\RpcClient($guzzle, $rpcUsername, $rpcPassword);
$logger = new \Monolog\Logger('transmission');
$logger->pushHandler(new \Monolog\Handler\StreamHandler('php://stdout'));
$api->setLogger($logger);
```

Now:

```php
$logger = new \Monolog\Logger('transmission');
$logger->pushHandler(new \Monolog\Handler\StreamHandler('php://stdout'));
$api = new \Martial\Transmission\API\RpcClient($guzzle, $rpcUsername, $rpcPassword, $logger);
```
