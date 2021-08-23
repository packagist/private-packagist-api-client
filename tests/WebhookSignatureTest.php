<?php

namespace PrivatePackagist\ApiClient;

use PHPUnit\Framework\TestCase;

class WebhookSignatureTest extends TestCase
{
    /** @var WebhookSignature */
    private $webhookSignature;

    protected function setUp(): void
    {
        $this->webhookSignature = new WebhookSignature('test');
    }

    public function testValidate()
    {
        $this->assertTrue($this->webhookSignature->validate('sha1=b92a2ae52a340f2246a0ec24d45bb4ecfdf7b8ac', 'payload'));
    }

    public function testValidateInvalid()
    {
        $this->assertFalse($this->webhookSignature->validate('sha1=invalid', 'payload'));
    }

    public function testValidateNull()
    {
        $this->assertFalse($this->webhookSignature->validate('sha1=invalid', null));
    }
}
