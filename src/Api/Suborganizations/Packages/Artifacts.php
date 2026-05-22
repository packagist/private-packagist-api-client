<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api\Suborganizations\Packages;

use PrivatePackagist\ApiClient\Api\AbstractApi;

class Artifacts extends AbstractApi
{
    public function create($suborganizationName, $file, $contentType, $fileName)
    {
        return $this->postFile(sprintf('/suborganizations/%s/packages/artifacts/', $suborganizationName), $file, array_filter([
            'Content-Type' => $contentType,
            'X-FILENAME' => $fileName
        ]));
    }

    public function add($suborganizationName, $packageIdOrName, $file, $contentType, $fileName)
    {
        return $this->postFile(sprintf('/suborganizations/%s/packages/%s/artifacts/', $suborganizationName, $packageIdOrName), $file, array_filter([
            'Content-Type' => $contentType,
            'X-FILENAME' => $fileName
        ]));
    }

    public function show($suborganizationName, $artifactId)
    {
        return $this->get(sprintf('/suborganizations/%s/packages/artifacts/%s/', $suborganizationName, $artifactId));
    }

    public function showPackageArtifacts($suborganizationName, $packageIdOrName)
    {
        return $this->getCollection(sprintf('/suborganizations/%s/packages/%s/artifacts/', $suborganizationName, $packageIdOrName));
    }
}
