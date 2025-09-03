<?php declare(strict_types=1);

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\HttpClient\Plugin;

use GuzzleHttp\Psr7\Request;
use Http\Promise\FulfilledPromise;
use PHPUnit\Framework\TestCase;

class PluginTestCase extends TestCase
{
    /** @var \Closure */
    protected $next;
    /** @var \Closure */
    protected $first;

    protected function setUp(): void
    {
        parent::setUp();

        $this->next = function (Request $request) {
            return new FulfilledPromise($request);
        };
        $this->first = function () {
            throw new \RuntimeException('Did not expect plugin to call first');
        };
    }
}
