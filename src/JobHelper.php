<?php

namespace PrivatePackagist\ApiClient;

use PrivatePackagist\ApiClient\Exception\JobErrorException;

class JobHelper
{
    /** @var Client */
    private $packagistClient;

    public function __construct(Client $packagistClient)
    {
        $this->packagistClient = $packagistClient;
    }

    public function waitForJob($jobId, $maxWaitMinutes = 3)
    {
        $maxWaitTime = new \DateTimeImmutable(sprintf('+%s minutes', $maxWaitMinutes));
        while ($maxWaitTime> new \DateTimeImmutable()) {
            $job = $this->packagistClient->jobs()->show($jobId);

            if ($job['status'] === 'success') {
                return $job;
            }

            if ($job['status'] === 'error') {
                throw new JobErrorException($job);
            }

            sleep(1);
        }

        throw new \Exception(sprintf('Job has not finish after %s minutes', $maxWaitMinutes));
    }
}
