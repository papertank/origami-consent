<?php

namespace Origami\Consent\Test;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Origami\Consent\GivesConsent;

class User extends Authenticatable
{
    use GivesConsent;

    protected $table = 'users';
    protected $fillable = ['email'];
    public $timestamps = false;
}
