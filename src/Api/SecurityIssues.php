<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

class SecurityIssues extends AbstractApi
{
    /**
     * Security issue that is still open
     */
    const STATE_OPEN = 'open';

    /**
     * Security issue where a fix is in progress
     */
    const STATE_IN_PROGRESS = 'in-progress';

    /**
     * Security issue that doesn't affect the project
     */
    const STATE_NOT_AFFECTED = 'not-affected';

    /**
     * Security issue that is incorrect
     */
    const STATE_INCORRECT = 'incorrect';

    /**
     * Security issue where there is no capacity to fix the issue
     */
    const STATE_NO_CAPACITY = 'no-capacity';

    /**
     * Security issue that can be ignored
     */
    const STATE_IGNORE = 'ignore';

    /**
     * Security issue that has been resolved
     */
    const STATE_RESOLVED = 'resolved';

    public function all(array $filters = [])
    {
        $filters = array_merge(['limit' => self::DEFAULT_LIMIT], $filters);

        return $this->get('/security-issues/', $filters);
    }

    public function show(int $issueId): array
    {
        return $this->get('/security-issues/' . $issueId);
    }

    public function open(int $issueId): array
    {
        return $this->post('/security-issues/' . $issueId . '/open');
    }

    public function close(int $issueId, string $state): array
    {
        return $this->post('/security-issues/' . $issueId . '/close/' . $state);
    }
}
