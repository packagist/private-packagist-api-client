<?php declare(strict_types=1);

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api\Customers;

use PHPUnit\Framework\MockObject\MockObject;
use PrivatePackagist\ApiClient\Api\AbstractApi;
use PrivatePackagist\ApiClient\Api\ApiTestCase;

class VendorBundlesTest extends ApiTestCase
{
    public function testListVendorBundles()
    {
        $expected = [
            [
                'expirationDate' => null,
                'vendorBundle' => ['id' => 12],
            ],
        ];

        /** @var VendorBundles&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/customers/1/vendor-bundles/'), $this->identicalTo(['limit' => AbstractApi::DEFAULT_LIMIT]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->listVendorBundles(1));
    }

    public function testAddOrEditVendorBundle()
    {
        $expected = [
            'expirationDate' => null,
            'vendorBundle' => ['id' => 12],
        ];

        /** @var VendorBundles&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/customers/1/vendor-bundles/'), $this->equalTo([
                'vendorBundleId' => 12,
                'expirationDate' => null,
            ]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->addOrEditVendorBundle(1, 12));
    }

    public function testRemoveVendorBundle()
    {
        $expected = '';

        /** @var VendorBundles&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo(sprintf('/customers/1/vendor-bundles/%s/', 12)))
            ->willReturn($expected);

        $this->assertSame($expected, $api->removeVendorBundle(1, 12));
    }

    protected function getApiClass()
    {
        return VendorBundles::class;
    }
}
