<?php

namespace Origami\Consent;

use Origami\Consent\Consent;

trait GivesConsent
{
    public function consent()
    {
        return $this->morphMany(Consent::class);
    }

    public function hasGivenConsent($name)
    {
        return $this->hasConsentedTo($name);
    }

    public function hasConsentedTo($name)
    {
        return Consent::get($this, $name)->given();
    }

    public function giveConsentTo($name)
    {
        return Consent::create([
            'model_id' => $this->getKey(),
            'model_type' => get_class($this),
            'name' => $name,
            'given' => true,
        ]);
    }

    public function revokeConsentTo($name)
    {
        return Consent::create([
            'model_id' => $this->getKey(),
            'model_type' => get_class($this),
            'name' => $name,
            'given' => false,
        ]);
    }

    public function scopeConsented($query, $names)
    {
        if (! is_array($names)) {
            $names = [$names];
        }

        return $query->whereHas('consent', function ($q) use ($names) {
            $q->whereIn('name', $names)
                ->where('given', '=', 1);
        }, '=', count($names));
    }
}
