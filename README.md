# Datagen

[![Latest Version](https://img.shields.io/github/release/shapintv/datagen.svg?style=flat-square)](https://github.com/shapintv/datagen/releases)
[![Build Status](https://img.shields.io/travis/shapintv/datagen.svg?style=flat-square)](https://travis-ci.com/shapintv/datagen)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/shapintv/datagen.svg?style=flat-square)](https://scrutinizer-ci.com/g/shapintv/datagen)
[![Quality Score](https://img.shields.io/scrutinizer/g/shapintv/datagen.svg?style=flat-square)](https://scrutinizer-ci.com/g/shapintv/datagen)
[![Total Downloads](https://img.shields.io/packagist/dt/shapin/datagen.svg?style=flat-square)](https://packagist.org/packages/shapin/datagen)

Datagen is a PHP library to deal with fixtures loading.

## Installation

The recommended way to install Datagen is through
[Composer](http://getcomposer.org/). Require the `shapin/datagen` package:

    $ composer require shapin/datagen

## Usage

The main entrypoint is the `Shapin\Datagen\Datagen` class and its `load` method.

```php
use Doctrine\DBAL\DriverManager;
use Shapin\Stripe\StripeClient;
use Symfony\Component\HttpClient\HttpClient;

use Shapin\Datagen\Datagen;
use Shapin\Datagen\DBAL\Processor as DBALProcessor;
use Shapin\Datagen\Loader;
use Shapin\Datagen\Stripe\Processor as StripeProcessor;
use Shapin\Datagen\ReferenceManager;

// Create a Loader for your fixtures
$loader = new Loader();
$loader->addFixture(new MyAwesomeFixture(), ['group1', 'group2']);
$loader->addFixture(new AnotherAwesomeFixture()); // Groups are faculative

$referenceManager = new ReferenceManager();

$connectionParams = [
    'dbname' => 'testDB',
    'user' => 'user',
    'password' => 'pass',
    'host' => 'localhost',
    'driver' => 'pdo_mysql',
];
$connection = DriverManager::getConnection($connectionParams);

$httpClient = HttpClient::create([
    'base_uri' => 'http://127.0.0.1:12111/v1/',
    'auth_bearer' => 'api_key',
    'headers' => [
        'Content-Type' => 'application/json',
    ],
]);
$stripeClient = new StripeClient($httpClient);

// Create your processors (see next section for more information regarding supported fixtures)
$processors = [
    new DBALProcessor($connection, $referenceManager),
    new StripeProcessor($stripeClient, $referenceManager),
];

// Create a Datagen
$datagen = new Datagen($loader, $processors);

// Load everything!
$datagen->load();
```

### Symfony

This library contains a bundle in order to integrate seamlessly with Symfony. For now, there isn't any symfony recipe so you'll need to manually register the bundle into your Kernel:

```php
    public function registerBundles()
    {
        $contents = require $this->getProjectDir().'/config/bundles.php';
        foreach ($contents as $class => $envs) {
            if ($envs[$this->environment] ?? $envs['all'] ?? false) {
                yield new $class();
            }
        }

        // No symfony recipe (yet) for Datagen
        if (in_array($this->environment, ['dev', 'test'])) {
            yield new \Shapin\Datagen\Bridge\Symfony\Bundle\ShapinDatagenBundle();
        }
    }
```

Once registered, you'll have access to new commands in order to play with datagen.

## Creating fixtures

### DBAL

In order to create a table and hydrate it, extends the `Table` base class:

```php
<?php

use Shapin\Datagen\DBAL\Table;
use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;

class Category extends Table
{
    protected static $tableName = 'category';
    protected static $order = 15;

    /**
     * {@inheritdoc}
     */
    public function addTableToSchema(Schema $schema)
    {
        $table = $schema->createTable(self::$tableName);
        $table->addColumn('id', 'uuid');
        $table->addColumn('name', 'string');
        $table->addColumn('description', 'text', ['notnull' => false]);

        $table->setPrimaryKey(['id']);
    }

    /**
     * {@inheritdoc}
     */
    public function getRows(): iterable
    {
        yield 'category_1' => [
            'id' => Uuid::uuid4(),
            'name' => 'Category 1',
            'description' => 'My awesome first category',
        ];

        yield 'another_category' => [
            'id' => Uuid::uuid4(),
            'name' => 'Another category',
        ];
    }
}
```

### Stripe

In order to create fixtures for Stripe, you'll need to use [shapintv/stripe](https://github.com/shapintv/stripe) library.

For example, if you want to create a product:

```php
<?php

use Shapin\Datagen\Stripe\Fixture;

class StripeProduct extends Fixture
{
    protected static $order = 30;

    /**
     * {@inheritdoc}
     */
    public function getObjectName(): string
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function getObjects(): iterable
    {
        yield 'my_product' => [
            'id' => 'my_product',
            'name' => 'This is my product!',
            'type' => 'product',
        ];
    }
}
```

## And so much more!

### Groups

When adding your fixtures to the Loader, you can use the second argument to specify some groups.
When launching the `Datagen::load` method, you can specify which groups you want to include and/or to exclude.

```php
// Load everything
$datagen->load();
// Load only fixtures from a given group
$datagen->load(['group1']);
// Do not load fixtures from a given group
$datagen->load([], ['group2']);
// Load all fixtures from group 1, ignoring group2. If a fixture is in both group, it will be ignored.
$datagen->load(['group1'], ['group2']);
```

### References

You can (and should!) name all your fixtures in order to be allowed to use them elsewhere.

```php
<?php

use Doctrine\DBAL\Schema\Schema;
use Shapin\Datagen\DBAL\Table;
use Ramsey\Uuid\Uuid;

class Category extends Table
{
    protected static $tableName = 'category';
    protected static $order = 15;

    /**
     * {@inheritdoc}
     */
    public function addTableToSchema(Schema $schema)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getRows(): iterable
    {
        // This fixture is not named and won't be referencable.
        yield [
            'id' => Uuid::uuid4(),
            'name' => 'Dead category',
        ];

        // The name of this fixture is "category_1". We'll use it later!
        yield 'category_1' => [
            'id' => Uuid::uuid4(),
            'name' => 'Category 1',
            'description' => 'My awesome first category',
        ];
    }
}

class Subcategory extends Table
{
    protected static $tableName = 'subcategory';
    protected static $order = 25;

    /**
     * {@inheritdoc}
     */
    public function addTableToSchema(Schema $schema)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getRows(): iterable
    {
        yield 'subcategory_1' => [
            'id' => Uuid::uuid4(),
            'category_id' => 'REF:category.category_1.id', // Here is our reference!
            'name' => 'Subcategory 1',
        ];
    }
}
```

## License

Datagen is released under the MIT License. See the bundled LICENSE file for details.
