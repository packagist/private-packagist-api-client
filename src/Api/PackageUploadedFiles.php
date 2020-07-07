<?php

/*
 * (c) Packagist Conductors UG (haftungsbeschränkt) <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

class PackageUploadedFiles extends AbstractApi
{
    public function create($file, $contentType, $contentLength, $fileName)
    {
        return $this->postFile('/packageuploadedfiles/', $file, array_filter([
            'Content-Type' => $contentType,
            'Content-Length' => $contentLength,
            'X-FILENAME' => $fileName
        ]));
    }
}
