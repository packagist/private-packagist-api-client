<?php

namespace PrivatePackagist\ApiClient\Api;

class Jobs extends AbstractApi
{
    public function show($jobId)
    {
        return $this->get(sprintf('/jobs/%s', $jobId));
    }
}
