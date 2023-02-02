<?php

namespace PrivatePackagist\ApiClient\Api;

class Synchronizations extends AbstractApi
{
    public function all()
    {
        return $this->get('/synchronizations/');
    }
}
