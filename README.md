# Laravel Connective Package Documentation

## Introduction

The Laravel Connective package provides a simple and intuitive way to establish connections between Eloquent models. It allows you to define different connection types and manage relationships between models.

## Key Concepts

### 1. Connection Types

- **Connection Types**: The package supports multiple connection types, which are defined in the package configuration. Users can connect models using one of these types.

### 2. Models

- **Connective Model**: Any Eloquent model can be used with the Connective package. To enable the package's functionality, a model must implement the `ConnectiveContract`.

### 3. Connections

- **Connection Model**: The package utilizes a `Connection` model to represent the relationships between different models. Each connection has a type, source model, and target model.

## Installation

To get started with the Laravel Connective package, follow these installation steps:

1. Install the package using Composer:

```bash
composer require aurorawebsoftware/connective
```

2. Publish the package configuration file:

```bash
php artisan vendor:publish --tag=connective-config
```

3. Update the connection types in the configuration file according to your needs.
```php
// config/connective.php
return [
    'connection_types' => ['friendship', 'ownership', 'parentage'],
];
```


## Usage

### Creating a *Connective* Model

To work with the Laravel Connective package, ensure that your models are set up correctly. Below are examples of how to create the necessary Eloquent models for the package:

Ensure that your models extend the appropriate Eloquent classes and implement the ConnectiveContract where necessary. Customize the models according to your application's requirements and business logic.

Here is the sample models

```php
use AuroraWebSoftware\Connective\Contracts\ConnectiveContract;
use AuroraWebSoftware\Connective\Models\Connection;
use AuroraWebSoftware\Connective\Traits\Connective;

class User extends Model implements ConnectiveContract
{
    use Connective

    public static function supportedConnectionTypes(): array
    {
        return ['friendship', 'parentage'];
    }

    // implementation of the model
}
```


```php
use AuroraWebSoftware\Connective\Contracts\ConnectiveContract;
use AuroraWebSoftware\Connective\Models\Connection;
use AuroraWebSoftware\Connective\Traits\Connective;

class Address extends Model implements ConnectiveContract
{
    use Connective

    public static function supportedConnectionTypes(): array
    {
        return ['home', 'office'];
    }

    // implementation of the model
}
```

### Establishing Connections between Models

To establish a connection between two models, you can use the `connectTo` method:

```php
$sourceModel->connectTo($targetModel, 'connection_type');
```

connect user1 to user2 with friendship (make friend1 and firend2 be friend)
> Connections are unidirectional; it is necessary to establish connections from each model if required.
```php
$user1->connectTo($user2, 'friendship');
$user2->connectTo($user1, 'friendship');
```

> Models can support multiple connection types and can accommodate multiple connections for the same model type.
```php
$user1->connectTo($address1, 'home');
$user1->connectTo($address2, 'office');
$user1->connectTo($address3, 'office');
```

### Retrieving Connections

You can retrieve connections for a model using the `connections` method. You can filter by connection type and target model type:

```php
$connections = $user1->connections('friendship');

// Retrieve all connections for the user
$connections = $user->connections();
// $connections is a collection of Connection models

```

### Retrieving Connected Models

To retrieve connected models (connective models) for a source model, you can use the `connectives` method. You can filter by connection type and target model type:

```php
$connectiveModels = $sourceModel->connectives('connection_type', 'target_model_type');
```

Retrieve friends of the user
```php
$friends = $user->connectives('friend');
```

// Retrieve residences and offices of the user
```php
$residences = $user->connectives(['residence', 'office'], Address::class);
// $residences is a collection of Address models (residences and offices addresses of the user)
```


### Nested Connections

The package allows you to establish nested connections. For example, if Model A is connected to Model B, and Model B is connected to Model C, you can retrieve Model C from Model A through the `connectives` method.
```php
$user = User::find(1);

// Retrieve friends of friends (nested connections)
$friendsOfFriends = $user->connectives('friend')->connectives('friend');

// $friendsOfFriends is a collection of User models (friends of friends)
```


```php
$user = User::find(1);

// Retrieve office addresses of friends (nested connections)
$officesOfFriends = $user->connectives('friend')->connectives('office', Address::class);
```

unlimited nesting
```php
$user = User::find(1);

// Retrieve a more complex nested connection
$complexNestedConnections = $user->connectives('friend')->connectives('residence')->connectives('collaborator');

// $complexNestedConnections is a collection of models based on the specified nested connections

```

## Conclusion

The Laravel Connective package simplifies managing relationships between Eloquent models by providing a straightforward and customizable solution. Explore the package's capabilities and tailor them to your project's needs.


---

This is a basic structure for your Laravel Connective package documentation. Be sure to expand and customize it further based on your package's specific features and requirements. Make sure to replace placeholder URLs and descriptions with actual content related to your package.