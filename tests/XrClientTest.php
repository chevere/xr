<?php

/*
 * This file is part of Chevere.
 *
 * (c) Rodolfo Berrios <rodolfo@chevere.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Chevere\Xr\Tests;

use Chevere\Xr\Client;
use Chevere\Xr\Curl;
use Chevere\Xr\Exceptions\StopException;
use Chevere\Xr\Message;
use Chevere\Xr\Tests\_resources\XrCurlError;
use Chevere\Xr\Tests\_resources\XrCurlLockTrue;
use Chevere\Xr\Tests\_resources\XrCurlStopTrue;
use PHPUnit\Framework\TestCase;

final class XrClientTest extends TestCase
{
    public function testDefault(): void
    {
        $client = new Client();
        $this->assertSame(
            'http://localhost:27420/endpoint',
            $client->getUrl('endpoint')
        );
    }

    public function testCustom(): void
    {
        $port = 12345;
        $host = 'test-host';
        $client = new Client(port: $port, host: $host);
        $this->assertSame(
            "http://$host:$port/endpoint",
            $client->getUrl('endpoint')
        );
        $message = new Message();
        $client->sendMessage($message);
        $this->assertFalse($client->isLocked($message));
    }

    public function testWithCurl(): void
    {
        $curl = new Curl();
        $client = (new Client())->withCurl($curl);
        $this->assertSame($curl, $client->curl());
    }

    public function testPauseLocked()
    {
        require_once __DIR__ . '/_resources/XrCurlLockTrue.php';
        $curl = new XrCurlLockTrue();
        $client = (new Client())->withCurl($curl);
        $message = new Message();
        $this->assertTrue($client->isLocked($message));
    }

    public function testPauseStop()
    {
        require_once __DIR__ . '/_resources/XrCurlStopTrue.php';
        $curl = new XrCurlStopTrue();
        $client = (new Client())->withCurl($curl);
        $message = new Message();
        $this->expectException(StopException::class);
        $client->sendPause($message);
    }

    public function testPauseError()
    {
        require_once __DIR__ . '/_resources/XrCurlError.php';
        $curl = new XrCurlError();
        $client = (new Client())->withCurl($curl);
        $message = new Message();
        $client->sendPause($message);
        $this->assertFalse($client->isLocked($message));
    }
}
