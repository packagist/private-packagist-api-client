<?php

/*
 * (c) Packagist Conductors UG (haftungsbeschränkt) <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

class PackageArtifacts extends AbstractApi
{
    public function create($file, $contentType, $fileName)
    {
        return $this->postFile('/packages/artifact/', $file, array_filter([
            'Content-Type' => $contentType,
            'X-FILENAME' => $fileName
        ]));
    }

    public function show($artifactId)
    {
        return $this->get(sprintf('/packages/artifact/%s/', $artifactId));
    }
}
