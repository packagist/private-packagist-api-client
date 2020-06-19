<?php

namespace PrivatePackagist\ApiClient\Exception;

class JobErrorException extends RuntimeException
{
    /** @var array */
    private $job;

    public function __construct(array $job)
    {
        $this->job = $job;

        parent::__construct($job['message']);
    }

    /**
     * @return array
     */
    public function getJob()
    {
        return $this->job;
    }
}
