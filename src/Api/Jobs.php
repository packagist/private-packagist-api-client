<?php

/*
 * (c) Packagist Conductors UG (haftungsbeschränkt) <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

class Jobs extends AbstractApi
{
    public function show($jobId)
    {
        return $this->get(sprintf('/jobs/%s', $jobId));
    }
}
