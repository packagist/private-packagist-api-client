<?php

namespace PrivatePackagist\ApiClient\Api;

class Packages extends AbstractApi
{
    public function all()
    {
        return $this->get('/packages/');
    }
}
