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
    private $defaultSubrepositoryAccess;

    /**
     * @param int[] $artifactPackageFileIds
     * @param ?string $defaultSubrepositoryAccess
     */
    public function __construct(array $artifactPackageFileIds, $defaultSubrepositoryAccess)
    {
        $this->artifactPackageFileIds = $artifactPackageFileIds;
        $this->defaultSubrepositoryAccess = $defaultSubrepositoryAccess;
    }

    /**
     * @return array{repoType: string, artifactIds: int[], defaultSubrepositoryAccess?: string}
     */
    public function toParameters(): array
    {
        $data = [
            'repoType' => 'artifact',
            'artifactIds' => $this->artifactPackageFileIds,
        ];

        if ($this->defaultSubrepositoryAccess) {
            $data['defaultSubrepositoryAccess'] = $this->defaultSubrepositoryAccess;
        }

        return $data;
    }
}
