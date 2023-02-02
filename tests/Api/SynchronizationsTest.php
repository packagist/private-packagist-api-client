<?php declare(strict_types=1);

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

        /** @var Teams&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/synchronizations/'))
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
