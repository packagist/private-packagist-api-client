<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api\VendorBundles;

use PHPUnit\Framework\MockObject\MockObject;
use PrivatePackagist\ApiClient\Api\AbstractApi;
use PrivatePackagist\ApiClient\Api\ApiTestCase;
use PrivatePackagist\ApiClient\Exception\InvalidArgumentException;

class PackagesTest extends ApiTestCase
{
    public function testListPackages()
    {
        $expected = [
            [
                'versionConstraint' => null,
                'minimumAccessibleStability' => null,
                'package' => [],
            ],
        ];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->identicalTo('/vendor-bundles/1/packages/'), $this->identicalTo(['limit' => AbstractApi::DEFAULT_LIMIT]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->listPackages(1));
    }

    public function testAddOrEditPackages()
    {
        $expected = [
            [
                'versionConstraint' => null,
                'minimumAccessibleStability' => null,
                'package' => [],
            ],
        ];

        $packages = [['name' => 'acme/package']];

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->identicalTo('/vendor-bundles/1/packages/'), $this->identicalTo($packages))
            ->willReturn($expected);

        $this->assertSame($expected, $api->addOrEditPackages(1, $packages));
    }

    public function testAddOrEditPackagesMissingName()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Parameter "name" is required.');

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();

        $api->addOrEditPackages(1, [[]]); // @phpstan-ignore-line
    }

    public function testRemovePackage()
    {
        $expected = '';
        $packageName = 'composer/composer';

        /** @var Packages&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->identicalTo(sprintf('/vendor-bundles/1/packages/%s/', $packageName)))
            ->willReturn($expected);

        $this->assertSame($expected, $api->removePackage(1, $packageName));
    }

    protected function getApiClass()
    {
        return Packages::class;
    }
}
