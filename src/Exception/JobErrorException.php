<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
