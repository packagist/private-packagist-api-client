<?php

namespace PrivatePackagist\ApiClient\Api;

use PrivatePackagist\ApiClient\Exception\InvalidArgumentException;

class Customers extends AbstractApi
{
    public function all()
    {
        return $this->get('/customers/');
    }

    public function create($name)
    {
        return $this->post('/customers/', ['name' => $name]);
    }

    public function remove($customerId)
    {
        return $this->delete(sprintf('/customers/%s/', $customerId));
    }

    public function listPackages($customerId)
    {
        return $this->get(sprintf('/customers/%s/packages/', $customerId));
    }

    public function addPackages($customerId, array $packages)
    {
        foreach ($packages as $package) {
            if (!isset($package['name'])) {
                throw new InvalidArgumentException('Parameter "name" is requried.');
            }
        }

        return $this->post(sprintf('/customers/%s/packages/', $customerId), $packages);
    }

    public function removePackage($customerId, $packageName)
    {
        return $this->delete(sprintf('/customers/%s/packages/%s/', $customerId, $packageName));
    }
}
