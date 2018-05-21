<?php

namespace Origami\Consent;

use Origami\Consent\Consent;
use Origami\Consent\Events\ConsentUpdated;

trait GivesConsent
{
    public function consents()
    {
        return $this->morphMany(Consent::class, 'model')->latest('created_at')->latest('id');
    }

    public function setConsent($name, $given, array $attributes = [])
    {
        $consent = $this->newConsent($name, $given, $attributes);
        $current = $this->getLatestConsent($name);

        if ($current && $consent->hasSameConsentAs($current)) {
            return $current;
        }

        $consent->save();

        event(new ConsentUpdated($this, $consent));

        return $consent;
    }

    protected function newConsent($name, $given, $attributes = [])
    {
        $consent = new Consent($attributes);
        $consent->name = $name;
        $consent->given = (boolean) $given;
        $consent->model()->associate($this);

        return $consent;
    }

    public function getConsent($name)
    {
        return $this->getLatestConsent($name);
    }

    public function getLatestConsent($name)
    {
        if ($this->relationLoaded('consents')) {
            return $this->consents->first(function ($consent) use ($name) {
                return $consent->name == $name;
            });
        }

        return $this->consents()->where('name', '=', $name)->first();
    }

    public function hasGivenConsent($name, $default = false)
    {
        return $this->hasConsentedTo($name, $default);
    }

    public function hasConsentedTo($name, $default = false)
    {
        $consent = $this->getLatestConsent($name);
        return $consent ? $consent->given() : $default;
    }

    public function giveConsentTo($name, array $attributes = [])
    {
        return $this->setConsent($name, true, $attributes);
    }

    public function revokeConsentTo($name, array $attributes = [])
    {
        return $this->setConsent($name, false, $attributes);
    }
}
