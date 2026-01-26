<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

class Synchronizations extends AbstractApi
{
    public function all()
    {
        return $this->get('/synchronizations/', ['limit' => self::DEFAULT_LIMIT]);
    }
}
