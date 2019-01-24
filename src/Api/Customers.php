<?php

namespace PrivatePackagist\ApiClient\Api;

use PrivatePackagist\ApiClient\Exception\InvalidArgumentException;

class Customers extends AbstractApi
{
    public function all()
    {
        return $this->get('/customers/');
    }

    public function show($customerId)
    {
        return $this->get(sprintf('/customers/%s/', $customerId));
    }

    public function create($name, $accessToVersionControlSource = false)
    {
        return $this->post('/customers/', ['name' => $name, 'accessToVersionControlSource' => $accessToVersionControlSource]);
    }

    /**
     * @deprecated Use edit instead
     */
    public function update($customerId, array $customer)
    {
        return $this->edit($customerId, $customer);
    }

    public function edit($customerId, array $customer)
    {
        return $this->put(sprintf('/customers/%s/', $customerId), $customer);
    }

    public function remove($customerId)
    {
        return $this->delete(sprintf('/customers/%s/', $customerId));
    }

    public function listPackages($customerId)
    {
        return $this->get(sprintf('/customers/%s/packages/', $customerId));
    }

    /**
     * @deprecated Use addOrEditPackages instead
     */
    public function addOrUpdatePackages($customerId, array $packages)
    {
        return $this->addOrEditPackages($customerId, $packages);
    }

    public function addOrEditPackages($customerId, array $packages)
    {
        foreach ($packages as $package) {
            if (!isset($package['name'])) {
                throw new InvalidArgumentException('Parameter "name" is required.');
            }
        }

        return $this->post(sprintf('/customers/%s/packages/', $customerId), $packages);
    }

    /**
     * @deprecated Use addOrEditPackages instead
     */
    public function addPackages($customerId, array $packages)
    {
        return $this->addOrEditPackages($customerId, $packages);
    }

    public function removePackage($customerId, $packageName)
    {
        return $this->delete(sprintf('/customers/%s/packages/%s/', $customerId, $packageName));
    }

    public function regenerateToken($customerId, array $confirmation)
    {
        if (!isset($confirmation['IConfirmOldTokenWillStopWorkingImmediately'])) {
            throw new InvalidArgumentException('Confirmation is required to regenerate the Composer repository token.');
        }

        return $this->post(sprintf('/customers/%s/token/regenerate', $customerId), $confirmation);
    }
}
