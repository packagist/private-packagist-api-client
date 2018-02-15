<?php

namespace PrivatePackagist\ApiClient\Api;

class Customers extends AbstractApi
{
    public function all()
    {
        return $this->get('/customers/');
    }

    public function create($name)
    {
        return $this->postRaw('/customers/', $this->createJsonBody(['name' => $name]));
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
        return $this->postRaw(sprintf('/customers/%s/packages/', $customerId), $this->createJsonBody($packages));
    }

    public function removePackage($customerId, $packageId)
    {
        return $this->delete(sprintf('/customers/%s/packages/%s/', $customerId, $packageId));
    }
}
