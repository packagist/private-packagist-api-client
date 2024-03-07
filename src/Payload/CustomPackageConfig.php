<?php declare(strict_types=1);

namespace PrivatePackagist\ApiClient\Payload;

/**
 * @internal
 * @final
 */
class CustomPackageConfig
{
    /** @var string */
    private $customJson;
    /** @var ?int */
    private $credentialId;
    /** @var ?string */
    private $defaultSubrepositoryAccess;

    /**
     * @param string|array|object $customJson
     * @param ?int $credentialId
     * @param ?string $defaultSubrepositoryAccess
     */
    public function __construct($customJson, $credentialId, $defaultSubrepositoryAccess)
    {
        if (is_array($customJson) || is_object($customJson)) {
            $customJson = json_encode($customJson);
        }

        $this->customJson = $customJson;
        $this->credentialId = $credentialId;
        $this->defaultSubrepositoryAccess = $defaultSubrepositoryAccess;
    }

    /**
     * @return array{repoType: string, repoConfig: string, credentials: ?int, defaultSubrepositoryAccess?: string}
     */
    public function toParameters(): array
    {
        $data = [
            'repoType' => 'package',
            'repoConfig' => $this->customJson,
            'credentials' => $this->credentialId,
        ];

        if ($this->defaultSubrepositoryAccess) {
            $data['defaultSubrepositoryAccess'] = $this->defaultSubrepositoryAccess;
        }

        return $data;
    }
}
