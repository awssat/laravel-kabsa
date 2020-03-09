# Laravel Kabsa 🍗


[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]



Laravel Kabsa is a simple array trait for your Eloquent model just like https://github.com/calebporzio/sushi without sqlite it's proof of concept so please don't use it in production 


## Install
To get started with Laravel Kabsa, use Composer to add the package to your project's dependencies:
```bash
composer require awssat/laravel-kabsa
```

## Use

1. Add the `Kabsa` trait to a model.
2. Add a `$rows` property to the model.

```php
class State extends Model
{
    use \Awssat\Kabsa\Traits\Kabsa;

    protected $rows = [
        [
            'abbr' => 'NY',
            'name' => 'New York',
        ],
        [
            'abbr' => 'CA',
            'name' => 'California',
        ],
    ];
}
```

Now, you can use this model anywhere you like, and it will behave as if you created a table with the rows you provided.
```php
$stateName = State::where('Abbr', 'NY')->first()->name;
```

### Relationships

```php
class Role extends Model
{
    use \Awssat\Kabsa\Traits\Kabsa;

    protected $rows = [
        ['label' => 'admin'],
        ['label' => 'manager'],
        ['label' => 'user'],
    ];
}
```

You can add a relationship to another standard model with help of new trait called `KabsaRelationships` right now I have just added two relationships hope we add more
```php
class User extends Model
{
    use \Awssat\Kabsa\Traits\KabsaRelationships;

    public function role()
    {
        return $this->belongsToKabsaRow(Role::class, 'label', 'role_label');
    }
}
```

the `users` table should have a `role_label` column, then:

```php
// Grab a User.
$user = User::first();
// Grab a Role.
$role = Role::where('label', 'admin')->first();

// Associate them.
$user->role()->associate($role);

// Access like normal.
$user->role;
```

Eager loading doen't work because it's a collection sorry :(

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/awssat/laravel-kabsa.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://travis-ci.org/awssat/laravel-kabsa.svg?branch=master
[ico-code-quality]: https://scrutinizer-ci.com/g/awssat/laravel-kabsa/badges/quality-score.png?b=master
[ico-downloads]: https://img.shields.io/packagist/dt/awssat/laravel-kabsa.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/awssat/laravel-kabsa
[link-travis]: https://travis-ci.org/awssat/laravel-kabsa
[link-scrutinizer]: https://scrutinizer-ci.com/g/awssat/laravel-kabsa/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/awssat/laravel-kabsa
[link-downloads]: https://packagist.org/packages/awssat/laravel-kabsa
[link-author]: https://github.com/if4lcon
[link-contributors]: ../../contributors
