<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

use PHPUnit\Framework\MockObject\MockObject;
use PrivatePackagist\ApiClient\Exception\InvalidArgumentException;

class CustomersTest extends ApiTestCase
{
    public function testAll()
    {
        $expected = [
            [
                'id' => 1,
                'type' => 'composer-repo',
                'name' => 'Customer',
                'assignAllPackages' => false,
            ],
        ];

        /** @var Customers&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/customers/'), $this->identicalTo(['limit' => AbstractApi::DEFAULT_LIMIT]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->all());
    }

    public function testShow()
    {
        $expected = [
            'id' => 1,
            'type' => 'composer-repo',
            'name' => 'Customer',
            'assignAllPackages' => false,
        ];

        /** @var Customers&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/customers/1/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->show(1));
    }

    public function testCreate()
    {
        $expected = [
            [
                'id' => 1,
                'type' => 'composer-repo',
                'name' => $name = 'Customer',
                'accessToVersionControlSource' => false,
                'minimumAccessibleStability' => 'dev',
                'assignAllPackages' => false,
            ],
        ];

        /** @var Customers&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/customers/'), $this->equalTo(['name' => $name, 'accessToVersionControlSource' => false, 'minimumAccessibleStability' => null, 'assignAllPackages' => false]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->create($name));
    }

    public function testCreateAllParameters()
    {
        $expected = [
            [
                'id' => 1,
                'type' => 'composer-repo',
                'name' => $name = 'Customer',
                'accessToVersionControlSource' => false,
                'minimumAccessibleStability' => 'beta',
                'assignAllPackages' => true,
            ],
        ];

        /** @var Customers&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/customers/'), $this->equalTo(['name' => $name, 'accessToVersionControlSource' => true, 'urlName' => 'url-name', 'minimumAccessibleStability' => 'beta', 'assignAllPackages' => true]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->create($name, true, 'url-name', 'beta', true));
    }

    public function tesEdit()
    {
        $expected = [
            [
                'id' => 1,
                'type' => 'composer-repo',
                'name' => $name = 'Customer',
                'urlName' => 'customer',
                'accessToVersionControlSource' => false,
                'minimumAccessibleStability' => 'dev',
                'assignAllPackages' => false,
            ],
        ];

        $customer = [
            'name' => $name,
            'urlName' => 'customer',
            'accessToVersionControlSource' => false,
            'minimumAccessibleStability' => null,
            'assignAllPackages' => false,
        ];

        /** @var Customers&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('put')
            ->with($this->equalTo('/customers/1/'), $this->equalTo($customer))
            ->willReturn($expected);

        $this->assertSame($expected, $api->edit(1, $customer));
    }

    public function testRemove()
    {
        $expected = '';

        /** @var Customers&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('/customers/1/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->remove(1));
    }

    public function testEnable()
    {
        $expected = [
            'id' => 1,
            'type' => 'composer-repo',
            'name' => $name = 'Customer',
            'urlName' => 'customer',
            'accessToVersionControlSource' => false,
            'enabled' => true,
            'assignAllPackages' => false,
        ];

        /** @var Customers&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('put')
            ->with($this->equalTo('/customers/1/enable'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->enable(1));
    }

    public function testDisable()
    {
        $expected = [
            'id' => 1,
            'type' => 'composer-repo',
            'name' => $name = 'Customer',
            'urlName' => 'customer',
            'accessToVersionControlSource' => false,
            'enabled' => false,
            'assignAllPackages' => false,
        ];

        /** @var Customers&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('put')
            ->with($this->equalTo('/customers/1/disable'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->disable(1));
    }

    public function testListPackages()
    {
        $expected = [
            [
                'name' => 'composer/composer',
                'origin' => 'private',
                'versionConstraint' => null,
                'expirationDate' => null,
            ],
        ];

        /** @var Customers&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/customers/1/packages/'), $this->identicalTo(['limit' => AbstractApi::DEFAULT_LIMIT]))
            ->willReturn($expected);

        $this->assertSame($expected, $api->listPackages(1));
    }

    public function testShowPackage()
    {
        $expected = [
            'name' => 'composer/composer',
            'origin' => 'private',
            'versionConstraint' => null,
            'expirationDate' => null,
            'versions' => [
                [
                    'version' => '2.3.9',
                    'versionNormalized' => '2.3.9.0',
                    'sourceReference' => '015f524c9969255a29cdea8890cbd4fec240ee47',
                    'distReference' => '015f524c9969255a29cdea8890cbd4fec240ee47',
                    'releasedAt' => '2022-07-05T14:52:00+00:00',
                ],
            ],
        ];

        /** @var Customers&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/customers/1/packages/composer/composer/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->showPackage(1, 'composer/composer'));
    }

    public function testAddOrEditPackages()
    {
        $expected = [
            [
                'name' => 'composer/composer',
                'origin' => 'private',
                'versionConstraint' => null,
                'expirationDate' => null,
            ],
        ];

        $packages = [['name' => 'composer/composer']];

        /** @var Customers&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/customers/1/packages/'), $this->equalTo($packages))
            ->willReturn($expected);

        $this->assertSame($expected, $api->addOrEditPackages(1, $packages));
    }

    public function testAddOrEditPackagesMissingName()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Parameter "name" is required.');

        /** @var Customers&MockObject $api */
        $api = $this->getApiMock();

        $api->addOrEditPackages(1, [[]]);
    }

    public function testRemovePackage()
    {
        $expected = '';
        $packageName = 'composer/composer';

        /** @var Customers&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with($this->equalTo(sprintf('/customers/1/packages/%s/', $packageName)))
            ->willReturn($expected);

        $this->assertSame($expected, $api->removePackage(1, $packageName));
    }

    public function testRegenerateToken()
    {
        $expected = [
            'url' => 'https://repo.packagist.com/acme-website/',
            'user' => 'token',
            'token' => 'regenerated-token',
            'lastUsed' => null,
        ];

        $confirmation = [
            'IConfirmOldTokenWillStopWorkingImmediately' => true,
        ];

        /** @var Customers&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/customers/1/token/regenerate'), $this->equalTo($confirmation))
            ->willReturn($expected);

        $this->assertSame($expected, $api->regenerateToken(1, $confirmation));
    }

    public function testRegenerateTokenMissingConfirmation()
    {
        $this->expectException(\InvalidArgumentException::class);

        /** @var Customers&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->never())
            ->method('post');

        $api->regenerateToken(1, []);
    }

    protected function getApiClass()
    {
        return Customers::class;
    }
}
