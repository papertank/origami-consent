<?php

namespace Origami\Consent\Events;

use Origami\Consent\Consent;
use Illuminate\Foundation\Events\Dispatchable;

class ConsentUpdated
{
    use Dispatchable;

    public $model;
    public $consent;

    public function __construct($model, Consent $consent)
    {
        $this->model = $model;
        $this->consent = $consent;
    }
}
