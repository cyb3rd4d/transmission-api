# PHP client for the Debian Transmission RPC API

![Build Status](http://ci.martialgeek.fr/buildStatus/icon?job=transmission-client)

## Purpose

I wrote this client because of a lack of features with other PHP clients. This implementation is full, tested
with PHPUnit and as close as possible from the original RPC interface.

## Installation

With composer:

```sh
composer require 'martial/transmission-api:~2.0'
```

## Usage

### Instantiation

```php
// Load composer autoloader

$httpClient = new GuzzleHttp\Client(['base_uri' => 'http://transmission-server:9091/transmission/rpc']);
$api = new \Martial\Transmission\API\RpcClient($httpClient, 'rpc-username', 'rpc-password');
```

### Show me what you're doing

You may want to use a logger:

```php
$logger = new \Monolog\Logger('transmission');
$logger->pushHandler(new \Monolog\Handler\StreamHandler('php://stdout'));

$api = new \Martial\Transmission\API\RpcClient($httpClient, 'rpc-username', 'rpc-password');
```

### Session ID

You must provide a session ID as first parameter of all API methods. This ID can be retrieved by calling any of
these methods with an invalid session ID, and by catching the \Martial\Transmission\API\CSRFException:

```php
$sessionId = '';

try {
    $api->sessionGet($sessionId);
} catch (\Martial\Transmission\API\CSRFException $e) {
    // The session has been reinitialized. Fetch the new session ID with the method getSessionId().
    $sessionId = $e->getSessionId();
} catch (\Martial\Transmission\API\TransmissionException $e) {
    // The API returned an error, retrieve the reason with the method getResult().
    die('API error: ' . $e->getResult());
}
```

### Method usage example

Then, just read the documentation of the interface \Martial\Transmission\API\TransmissionAPI. Each method is documented:

```php
try {
    $api->torrentAdd($sessionId, [
        \Martial\Transmission\API\Argument\Torrent\Add::FILENAME => '/path/to/the/torrent/file.torrent'
    ]);
} catch (\Martial\Transmission\API\DuplicateTorrentException $e) {
    // This torrent is already in your download queue.
} catch (\Martial\Transmission\API\MissingArgumentException $e) {
    // Some required arguments are missing.
} catch (\Martial\Transmission\API\CSRFException $e) {
    // The session has been reinitialized. Fetch the new session ID with the method getSessionId().
} catch (\Martial\Transmission\API\TransmissionException $e) {
    // The API returned an error, retrieve the reason with the method getResult().
    die('API error: ' . $e->getResult());
}
```
