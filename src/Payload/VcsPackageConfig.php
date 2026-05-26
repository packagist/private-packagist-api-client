<?php declare(strict_types=1);

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Payload;

/**
 * @internal
 * @final
 */
class VcsPackageConfig
{
    /** @var string */
    private $url;
    /** @var ?int */
    private $credentialId;
    /** @var string */
    private $type;
    /** @var ?string */
    private $defaultSuborganizationAccess;

    /**
     * @param string $url
     * @param ?int $credentialId
     * @param string $type
     * @param ?string $defaultSuborganizationAccess
     */
    public function __construct($url, $credentialId, $type, $defaultSuborganizationAccess)
    {
        $this->url = $url;
        $this->credentialId = $credentialId;
        $this->type = $type;
        $this->defaultSuborganizationAccess = $defaultSuborganizationAccess;
    }

    /**
     * @return array{repoType: string, repoUrl: string, credentials: ?int, defaultSuborganizationAccess?: string}
     */
    public function toParameters(): array
    {
        $data = [
            'repoType' => $this->type,
            'repoUrl' => $this->url,
            'credentials' => $this->credentialId,
        ];

        if ($this->defaultSuborganizationAccess) {
            $data['defaultSuborganizationAccess'] = $this->defaultSuborganizationAccess;
        }

        return $data;
    }
}
