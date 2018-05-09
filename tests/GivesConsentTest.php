<?php

namespace Origami\Consent\Test;

use Spatie\Permission\Contracts\Role;

class GivesConsentTest extends TestCase
{
    /** @test */
    public function it_can_assume_no_consent()
    {
        $this->assertFalse($this->testUser->hasGivenConsent('non-existent'));
    }

    /** @test */
    public function it_can_change_assumed_consent()
    {
        $this->assertTrue($this->testUser->hasGivenConsent('non-existent', true));
    }

    /** @test */
    public function it_can_give_consent()
    {
        $this->testUser->giveConsentTo('emails');

        $this->assertTrue($this->testUser->hasConsentedTo('emails'));

        $this->refreshTestUser();

        $this->assertTrue($this->testUser->hasConsentedTo('emails'));
    }

    /** @test */
    public function it_can_give_consent_with_text()
    {
        $text = 'You are consenting to receive emails';

        $this->testUser->giveConsentTo('emails', [
            'text' => $text
        ]);

        $this->refreshTestUser();

        $consent = $this->testUser->getConsent('emails');

        $this->assertEquals($text, $consent->text);
    }

    /** @test */
    public function it_can_give_consent_with_meta()
    {
        $meta = [
            'ip_address' => '127.0.0.1'
        ];

        $this->testUser->giveConsentTo('emails', [
            'meta' => $meta,
        ]);

        $this->refreshTestUser();

        $consent = $this->testUser->getConsent('emails');

        $this->assertEquals($meta, $consent->meta);
    }

    /** @test */
    public function it_can_revoke_consent()
    {
        $this->testUser->giveConsentTo('emails');

        $this->assertTrue($this->testUser->hasConsentedTo('emails'));

        $this->refreshTestUser();

        $this->testUser->revokeConsentTo('emails');

        $this->assertFalse($this->testUser->hasConsentedTo('emails'));
    }

    /** @test */
    public function it_can_keep_current_consent()
    {
        $this->testUser->giveConsentTo('emails');

        $this->assertTrue($this->testUser->hasConsentedTo('emails'));

        $this->refreshTestUser();

        $this->testUser->giveConsentTo('emails');

        $this->assertEquals(1, $this->testUser->consents()->count());
    }

    /** @test */
    public function it_can_change_current_consent_when_different_text()
    {
        $this->testUser->giveConsentTo('emails', ['text' => 'Original text']);
        $this->refreshTestUser();
        $this->testUser->giveConsentTo('emails', ['text' => 'New text']);

        $this->assertEquals(2, $this->testUser->consents()->count());
    }
}
