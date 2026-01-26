<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

use PHPUnit\Framework\MockObject\MockObject;

class VendorBundlesTest extends ApiTestCase
{
    public function testAll()
    {
        $expected = [
            [
                'id' => 1,
                'name' => 'Bundle',
                'versionConstraint' => null,
                'minimumAccessibleStability' => null,
                'assignAllPackages' => false,
                'synchronizations' => [],
            ],
        ];

        /** @var VendorBundles&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->identicalTo('/vendor-bundles/'), $this->identicalTo(['limit' => AbstractApi::DEFAULT_LIMIT]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->all());
    }

    public function testShow()
    {
        $expected = [
            'id' => 1,
            'name' => 'Bundle',
            'versionConstraint' => null,
            'minimumAccessibleStability' => null,
            'assignAllPackages' => false,
            'synchronizations' => [],
        ];

        /** @var VendorBundles&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->identicalTo('/vendor-bundles/1/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->show(1));
    }

    public function testCreate()
    {
        $expected = [
            'id' => 1,
            'name' => 'Bundle',
            'versionConstraint' => null,
            'minimumAccessibleStability' => null,
            'assignAllPackages' => false,
            'synchronizations' => [],
        ];

        /** @var VendorBundles&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->identicalTo('/vendor-bundles/'), $this->identicalTo([
                'name' => 'Bundle',
                'minimumAccessibleStability' => null,
                'versionConstraint' => null,
                'assignAllPackages' => false,
                'synchronizationIds' => [],
            ]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->create('Bundle'));
    }

    public function testCreateAllParameters()
    {
        $expected = [
            'id' => 1,
            'name' => 'Bundle',
            'versionConstraint' => '^1.0',
            'minimumAccessibleStability' => 'stable',
            'assignAllPackages' => true,
            'synchronizations' => [
                ['id' => 123],
            ],
        ];

        /** @var VendorBundles&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->identicalTo('/vendor-bundles/'), $this->identicalTo([
                'name' => 'Bundle',
                'minimumAccessibleStability' => 'stable',
                'versionConstraint' => '^1.0',
                'assignAllPackages' => true,
                'synchronizationIds' => [123],
            ]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->create('Bundle', 'stable', '^1.0', true, [123]));
    }

    public function tesEdit()
    {
        $expected = [
            'id' => 1,
            'name' => 'Bundle',
            'versionConstraint' => '^1.0',
            'minimumAccessibleStability' => 'stable',
            'assignAllPackages' => true,
            'synchronizations' => [
                ['id' => 123],
            ],
        ];

        $bundle = [
            'id' => 1,
            'name' => 'Bundle',
            'versionConstraint' => '^1.0',
            'minimumAccessibleStability' => 'stable',
            'assignAllPackages' => true,
            'synchronizationIds' => [123],
        ];

        /** @var VendorBundles&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('put')
            ->with($this->identicalTo('/vendor-bundles/1/'), $this->identicalTo($bundle))
            ->willReturn($expected);

        $this->assertSame($expected, $api->edit(1, $bundle));
    }

    public function testRemove()
    {
        $expected = '';

        /** @var VendorBundles&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->identicalTo('/vendor-bundles/1/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->remove(1));
    }

    protected function getApiClass()
    {
        return VendorBundles::class;
    }
}
