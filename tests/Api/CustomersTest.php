<?php

/*
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
            ],
        ];

        /** @var Customers&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with($this->equalTo('/customers/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->all());
    }

    public function testShow()
    {
        $expected = [
            'id' => 1,
            'type' => 'composer-repo',
            'name' => $name = 'Customer',
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
            ],
        ];

        /** @var Customers&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/customers/'), $this->equalTo(['name' => $name, 'accessToVersionControlSource' => false, 'minimumAccessibleStability' => null]))
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
                'minimumAccessibleStability' => 'beta'
            ],
        ];

        /** @var Customers&MockObject $api */
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('post')
            ->with($this->equalTo('/customers/'), $this->equalTo(['name' => $name, 'accessToVersionControlSource' => true, 'urlName' => 'url-name', 'minimumAccessibleStability' => 'beta']))
            ->willReturn($expected);

        $this->assertSame($expected, $api->create($name, true, 'url-name', 'beta'));
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
            ],
        ];

        $customer = [
            'name' => $name,
            'urlName' => 'customer',
            'accessToVersionControlSource' => false,
            'minimumAccessibleStability' => null,
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
            ->with($this->equalTo('/customers/1/packages/'))
            ->willReturn($expected);

        $this->assertSame($expected, $api->listPackages(1));
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
