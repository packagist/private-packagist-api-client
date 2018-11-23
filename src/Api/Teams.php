<?php

namespace PrivatePackagist\ApiClient\Api;

class Teams extends AbstractApi
{
    public function all()
    {
        return $this->get('/teams/');
    }
}
