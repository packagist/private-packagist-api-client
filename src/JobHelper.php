<?php

namespace PrivatePackagist\ApiClient;

use PrivatePackagist\ApiClient\Exception\JobErrorException;
use PrivatePackagist\ApiClient\Exception\JobTimeoutException;

class JobHelper
{
    /** @var Client */
    private $packagistClient;

    public function __construct(Client $packagistClient)
    {
        $this->packagistClient = $packagistClient;
    }

    /**
     * @param string $jobId
     * @param int $maxWaitSeconds
     * @param int $waitInterval
     * @return array
     */
    public function waitForJob($jobId, $maxWaitSeconds = 180, $waitInterval = 5)
    {
        $maxWaitTime = new \DateTimeImmutable(sprintf('+%s seconds', $maxWaitSeconds));
        while ($maxWaitTime> new \DateTimeImmutable()) {
            $job = $this->packagistClient->jobs()->show($jobId);

            if ($job['status'] === 'success') {
                return $job;
            }

            if ($job['status'] === 'error') {
                throw new JobErrorException($job);
            }

            sleep($waitInterval);
        }

        throw new JobTimeoutException(sprintf('Job has not finish after %s seconds', $maxWaitSeconds));
    }
}
