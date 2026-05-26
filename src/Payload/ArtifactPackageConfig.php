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
class ArtifactPackageConfig
{
    /** @var int[] */
    private $artifactPackageFileIds;
    /** @var ?string */
    private $defaultSuborganizationAccess;

    /**
     * @param int[] $artifactPackageFileIds
     * @param ?string $defaultSuborganizationAccess
     */
    public function __construct(array $artifactPackageFileIds, $defaultSuborganizationAccess)
    {
        $this->artifactPackageFileIds = $artifactPackageFileIds;
        $this->defaultSuborganizationAccess = $defaultSuborganizationAccess;
    }

    /**
     * @return array{repoType: string, artifactIds: int[], defaultSuborganizationAccess?: string}
     */
    public function toParameters(): array
    {
        $data = [
            'repoType' => 'artifact',
            'artifactIds' => $this->artifactPackageFileIds,
        ];

        if ($this->defaultSuborganizationAccess) {
            $data['defaultSuborganizationAccess'] = $this->defaultSuborganizationAccess;
        }

        return $data;
    }
}
