<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api\Packages;

use PrivatePackagist\ApiClient\Api\AbstractApi;

class Artifacts extends AbstractApi
{
    public function create($file, $contentType, $fileName)
    {
        return $this->postFile('/packages/artifacts/', $file, array_filter([
            'Content-Type' => $contentType,
            'X-FILENAME' => $fileName
        ]));
    }

    public function add($packageIdOrName, $file, $contentType, $fileName)
    {
        return $this->postFile('/packages/'.$packageIdOrName.'/artifacts/', $file, array_filter([
            'Content-Type' => $contentType,
            'X-FILENAME' => $fileName
        ]));
    }

    public function show($artifactId)
    {
        return $this->get(sprintf('/packages/artifacts/%s/', $artifactId));
    }

    public function showPackageArtifacts($packageIdOrName)
    {
        return $this->get(sprintf('/packages/%s/artifacts/', $packageIdOrName), ['limit' => self::DEFAULT_LIMIT]);
    }
}
