<?php

namespace Origami\Consent;

use Illuminate\Database\Eloquent\Model;

class Consent extends Model
{
    protected $fillable = ['name', 'text', 'model_id', 'model_type', 'given', 'meta'];
    protected $casts = ['given' => 'boolean', 'meta' => 'array'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(config('consent.table'));
    }

    public function model()
    {
        return $this->morphTo();
    }

    public function hasSameConsentAs(Consent $consent)
    {
        return ($this->given == $consent->given) &&
                ($this->text == $consent->text);
    }

    public function hasChangedConsent()
    {
        return $this->isDirty(['given', 'text']);
    }

    public function given()
    {
        return $this->given;
    }
}
