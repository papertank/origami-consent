<?php

namespace Origami\Consent\Test;

use Spatie\Permission\Contracts\Role;

class GivesConsentTest extends TestCase
{
    /** @test */
    public function it_can_determine_that_the_user_does_not_have_consent()
    {
        $this->assertFalse($this->testUser->hasGivenConsent('non-existent'));
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
    public function it_can_revoke_consent()
    {
        $this->testUser->giveConsentTo('emails');

        $this->assertTrue($this->testUser->hasConsentedTo('emails'));

        $this->refreshTestUser();

        $this->testUser->revokeConsentTo('emails');

        $this->assertFalse($this->testUser->hasConsentedTo('emails'));
    }
}
