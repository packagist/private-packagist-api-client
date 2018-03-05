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
        return $this->post(sprintf('/customers/%s/packages/', $customerId), $packages);
    }

    public function removePackage($customerId, $packageName)
    {
        return $this->delete(sprintf('/customers/%s/packages/%s/', $customerId, $packageName));
    }
}
