<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

use PrivatePackagist\ApiClient\Exception\InvalidArgumentException;

class Tokens extends AbstractApi
{
    public function all()
    {
        return $this->get('/tokens/', ['limit' => self::DEFAULT_LIMIT]);
    }

    public function create(array $tokenData)
    {
        if (isset($tokenData['teamId'], $tokenData['accessToAllPackages'])) {
            throw new InvalidArgumentException('Only set either "accessToAllPackages" or "teamId"');
        }

        return $this->post('/tokens/', $tokenData);
    }

    public function remove($tokenId)
    {
        return $this->delete(sprintf('/tokens/%s/', $tokenId));
    }

    public function regenerate($tokenId, array $confirmation)
    {
        if (!isset($confirmation['IConfirmOldTokenWillStopWorkingImmediately'])) {
            throw new InvalidArgumentException('Confirmation is required to regenerate the Composer repository token.');
        }

        return $this->post(sprintf('/tokens/%s/regenerate', $tokenId), $confirmation);
    }
}
