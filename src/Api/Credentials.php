<?php

/*
 * (c) Packagist Conductors UG (haftungsbeschränkt) <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

class Credentials extends AbstractApi
{
    const TYPE_BITBUCKET_APP_PW = 'bitbucket-app-pw';
    const TYPE_GITHUB_OAUTH = 'github-oauth';
    const TYPE_GITLAB_TOKEN = 'gitlab-token';
    const TYPE_HTTP_BASIC = 'http-basic';
    const TYPE_MAGENTO = 'magento';
    const TYPE_HTTP_HEADER = 'http-header';

    public function all()
    {
        return $this->get('/credentials/');
    }

    public function show($credentialId)
    {
        return $this->get(sprintf('/credentials/%s/', $credentialId));
    }

    public function create($description, $type, $domain, $username, $credential)
    {
        return $this->post('/credentials/', [
            'description' => $description,
            'type' => $type,
            'domain' => $domain,
            'username' => $username,
            'credential' => $credential,
        ]);
    }

    /**
     * @deprecated Use edit instead
     */
    public function update($credentialId, $type, $username, $credential)
    {
        return $this->edit($credentialId, $type, $username, $credential);
    }

    public function edit($credentialId, $type, $username, $credential)
    {
        return $this->put(sprintf('/credentials/%s/', $credentialId), [
            'username' => $username,
            'credential' => $credential,
            'type' => $type,
        ]);
    }

    public function remove($credentialId)
    {
        return $this->delete(sprintf('/credentials/%s/', $credentialId));
    }
}
