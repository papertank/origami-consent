# Laravel Consent [![Build Status](https://travis-ci.org/papertank/origami-consent.svg?branch=master)](https://travis-ci.org/papertank/origami-consent)

## About

The `origami/consent` helper package contains a Laravel model trait to make saving, comparing and revoking consent easier. The package saves all updates to consent to the `consent` table and provides a `GivesConsent` trait for models like the User model.

The necessity for the package came about through GDPR and the [UK Information Commissioner's Office guidance on "Consent"](https://ico.org.uk/for-organisations/guide-to-the-general-data-protection-regulation-gdpr/lawful-basis-for-processing/consent/).


## Installation

This package is designed for Laravel 5.4+. You can pull in this package through composer

```
composer require origami/consent
```

You should publish the consent table migration with:

```
php artisan vendor:publish --provider="Origami\Consent\ConsentServiceProvider" --tag="migrations"
```

The migrate the database:

```
php artisan migrate
```

## Usage

To use the package, add the `GivesConsent` trait to a model you'd like to track consent for.

```php

use Origami\Consent\GivesConsent;

class User extends Model {

  use GivesConsent;

}

```

### Give consent

You can mark explicit consent given like this:

```php

$model->giveConsentTo('consent-name');

```

GDPR requires you to keep a record of exactly what was shown at the time. You can do this in the `text` attribute, and pass anything extra in `meta`

```php

$model->giveConsentTo('consent-name', [
  'text' => 'You are consenting to ...',
  'meta' => [
    'ip' => '192.168.0.1',
  ]
]);

```

### Give consent

You can revoke a user's consent like so:

```php

$model->revokeConsentTo('consent-name');

```

### Checking consent

You can check if consent is given like so:

```php

if ( $model->hasGivenConsent('consent-name') ) {

}

```

If consent has not been set, the default is `false`. You can change that in the 2nd paramter.

```php

if ( $model->hasGivenConsent('consent-name', true) ) {

}

```

### Current consent

You can get the user's current consent status like so:

```php

$consent = $model->getConsent('consent-name');

```


## Contributing

Please submit improvements and fixes :)

## Author
[Papertank Limited](https://papertank.com)

## License
[View the license](https://github.com/papertank/origami-consent/blob/master/LICENSE)
