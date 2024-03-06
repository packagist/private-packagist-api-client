<?php declare(strict_types=1);

namespace PrivatePackagist\ApiClient\Payload;

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
