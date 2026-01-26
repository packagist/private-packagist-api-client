<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api\Customers;

use PrivatePackagist\ApiClient\Api\AbstractApi;

class MagentoLegacyKeys extends AbstractApi
{
    public function all($customerIdOrUrlName)
    {
        return $this->get(sprintf('/api/customers/%s/magento-legacy-keys/', $customerIdOrUrlName), ['limit' => self::DEFAULT_LIMIT]);
    }

    public function create($customerIdOrUrlName, $publicKey, $privateKey)
    {
        return $this->post(sprintf('/api/customers/%s/magento-legacy-keys/', $customerIdOrUrlName), [
            'publicKey' => $publicKey,
            'privateKey' => $privateKey,
        ]);
    }

    public function remove($customerIdOrUrlName, $publicKey)
    {
        return $this->delete(sprintf('/api/customers/%s/magento-legacy-keys/%s/', $customerIdOrUrlName, $publicKey));
    }
}
