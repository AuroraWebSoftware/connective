## Laravel Connective

This package provides a flexible system for creating typed connections between Eloquent models with support for nested relationships and inverse lookups.

### Setup

Models must implement `ConnectiveContract` and use the `Connective` trait:

@verbatim
<code-snippet name="Basic Model Setup" lang="php">
use AuroraWebSoftware\Connective\Contracts\ConnectiveContract;
use AuroraWebSoftware\Connective\Traits\Connective;

class User extends Model implements ConnectiveContract
{
    use Connective;

    public static function supportedConnectionTypes(): array
    {
        return ['friendship', 'family', 'colleague'];
    }
}
</code-snippet>
@endverbatim

### Creating Connections

Connections are unidirectional. Create both directions if needed:

@verbatim
<code-snippet name="Connect Two Users" lang="php">
// One-way connection
$user1->connectTo($user2, 'friendship');

// Two-way friendship
$user1->connectTo($user2, 'friendship');
$user2->connectTo($user1, 'friendship');
</code-snippet>
@endverbatim

@verbatim
<code-snippet name="Multiple Connection Types" lang="php">
// User can have multiple addresses with different types
$user->connectTo($homeAddress, 'home');
$user->connectTo($officeAddress1, 'office');
$user->connectTo($officeAddress2, 'office');
</code-snippet>
@endverbatim

### Retrieving Connections

Get `Connection` model instances:

@verbatim
<code-snippet name="Get Connections" lang="php">
// All connections
$connections = $user->connections();

// Filter by type
$friendConnections = $user->connections('friendship');

// Filter by type and model
$officeAddresses = $user->connections('office', Address::class);

// Multiple types
$homeAndOffice = $user->connections(['home', 'office'], Address::class);
</code-snippet>
@endverbatim

### Retrieving Connected Models

Get actual model instances (not Connection models):

@verbatim
<code-snippet name="Get Connected Models" lang="php">
// Get all friends
$friends = $user->connectives('friendship');

// Get addresses of specific types
$addresses = $user->connectives(['home', 'office'], Address::class);

// Ignore global scopes
$allFriends = $user->connectives('friendship', User::class, []);
$specificFriends = $user->connectives('friendship', User::class, [ActiveScope::class]);
</code-snippet>
@endverbatim

### Nested Connections

Chain `connectives()` for unlimited depth:

@verbatim
<code-snippet name="Nested Relationships" lang="php">
// Friends of friends
$friendsOfFriends = $user->connectives('friendship')->connectives('friendship');

// Office addresses of friends
$friendOffices = $user->connectives('friendship')->connectives('office', Address::class);

// Complex nesting
$result = $user
    ->connectives('friendship')
    ->connectives('family')
    ->connectives('home', Address::class);
</code-snippet>
@endverbatim

### Inverse Connections

Find who connected TO this model:

@verbatim
<code-snippet name="Inverse Lookups" lang="php">
// Get Connection models where this user is the target
$incomingConnections = $user->inverseConnections('friendship');

// Get User models who connected to this user
$followers = $user->inverseConnectives('friendship', User::class);

// With scope control
$allFollowers = $user->inverseConnectives('friendship', User::class, []);
</code-snippet>
@endverbatim

### Configuration

Define global connection types in `config/connective.php`:

@verbatim
<code-snippet name="Config File" lang="php">
return [
    'connection_types' => [
        'friendship',
        'family',
        'colleague',
        'home',
        'office',
        'ownership',
    ],
];
</code-snippet>
@endverbatim

### Common Patterns

@verbatim
<code-snippet name="Social Network" lang="php">
// Mutual friendship check
$user1Friends = $user1->connectives('friendship')->pluck('id');
$user2Friends = $user2->connectives('friendship')->pluck('id');
$areMutualFriends = $user1Friends->contains($user2->id)
    && $user2Friends->contains($user1->id);

// Friend suggestions (friends of friends who aren't already friends)
$friendsOfFriends = $user->connectives('friendship')
    ->connectives('friendship')
    ->reject(fn($u) => $u->id === $user->id)
    ->reject(fn($u) => $user1Friends->contains($u->id));
</code-snippet>
@endverbatim

@verbatim
<code-snippet name="Multi-Model Scenario" lang="php">
class Organization extends Model implements ConnectiveContract
{
    use Connective;

    public static function supportedConnectionTypes(): array
    {
        return ['employee', 'partner', 'vendor'];
    }
}

// Organization connections
$org->connectTo($user, 'employee');
$org->connectTo($vendor, 'vendor');

// Get all employees
$employees = $org->connectives('employee', User::class);

// Get all users who work at this org
$workers = $org->inverseConnectives('employee', User::class);
</code-snippet>
@endverbatim

### Best Practices

- Always define `supportedConnectionTypes()` to validate connection types
- Use two-way connections for symmetric relationships (friendship)
- Use one-way connections for asymmetric relationships (following)
- Use `connections()` when you need Connection model metadata
- Use `connectives()` when you need the actual connected models
- Use `inverseConnectives()` for reverse lookups (who follows me, who owns this)
- Pass `ignoreScopes` parameter when you need soft-deleted or filtered models
