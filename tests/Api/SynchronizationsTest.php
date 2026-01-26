<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

use PHPUnit\Framework\MockObject\MockObject;

class SynchronizationsTest extends ApiTestCase
{
    public function testAll()
    {
        $expected = [
            [
                "id" => 42,
                "name" => "Acme Organization",
                "isPrimary" => true,
                "integration" => [
                    "name" => "GitHub",
                    "target" => "github",
                    "url" => "https://github.com"
                ],
                "credentials" => 432,
            ]
        ];

        /** @var Synchronizations&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/synchronizations/'), $this->identicalTo(['limit' => AbstractApi::DEFAULT_LIMIT]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->all());
    }

    /**
     * @return string
     */
    protected function getApiClass()
    {
        return Synchronizations::class;
    }
}
