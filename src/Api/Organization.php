<?php

namespace PrivatePackagist\ApiClient\Api;

class Organization extends AbstractApi
{
    public function sync()
    {
        return $this->post('/organization/sync');
    }
}
