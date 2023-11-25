<?php

use AuroraWebSoftware\Connective\Contracts\ConnectiveContract;
use AuroraWebSoftware\Connective\Exceptions\ConnectionTypeException;
use AuroraWebSoftware\Connective\Facades\Connective;
use AuroraWebSoftware\Connective\Models\Connection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    Artisan::call('migrate:fresh');

    //include_once __DIR__.'/../database/migrations/2023_10_11_192125_create_connectives_table.php';
    //(new create_connective_tables)->up();

    Config::set(
        [
            'connective' => [
                'connection_types' => ['a', 'b', 'c'],
            ],
        ]
    );

    Schema::create('connectives', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->timestamps();
    });

    Schema::create('other_connectives', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->timestamps();
    });

    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->timestamps();
    });

    $user = User::create();
    $this->actingAs($user);

});

it('can get all connection types', function () {
    expect(Connective::connectionTypes())
        ->toBeArray()->toHaveCount(3)->toContain('a', 'b', 'c');
});

it('can create a Connective Test Model', function () {

    $connective1 = \AuroraWebSoftware\Connective\Tests\Models\Connective::create([
        'name' => 'name1',
    ]);

    expect($connective1)->toBeTruthy();

});

it('can get ConnectionTypeException when tring to use nonexisting connection type', function () {

    /**
     * @var ConnectiveContract & \AuroraWebSoftware\Connective\Tests\Models\Connective $connective2
     */
    $connective2 = \AuroraWebSoftware\Connective\Tests\Models\Connective::create([
        'name' => 'name10',
    ]);

    /**
     * @var ConnectiveContract & \AuroraWebSoftware\Connective\Tests\Models\Connective $connective3
     */
    $connective3 = \AuroraWebSoftware\Connective\Tests\Models\Connective::create([
        'name' => 'name11',
    ]);

    $connective2->connectTo($connective3, 'x');
})->expectException(ConnectionTypeException::class);

it('can get ConnectionTypeNotSupportedException when tring to use not supported connection type', function () {

    /**
     * @var ConnectiveContract & \AuroraWebSoftware\Connective\Tests\Models\Connective $connective2
     */
    $connective2 = \AuroraWebSoftware\Connective\Tests\Models\Connective::create([
        'name' => 'name20',
    ]);

    /**
     * @var ConnectiveContract & \AuroraWebSoftware\Connective\Tests\Models\Connective $connective3
     */
    $connective3 = \AuroraWebSoftware\Connective\Tests\Models\Connective::create([
        'name' => 'name21',
    ]);

    $connective2->connectTo($connective3, 'c');
})->expectException(\AuroraWebSoftware\Connective\Exceptions\ConnectionTypeNotSupportedException::class);

it('can connectTo a Connective Model', function () {

    /**
     * @var ConnectiveContract & \AuroraWebSoftware\Connective\Tests\Models\Connective $connective2
     */
    $connective2 = \AuroraWebSoftware\Connective\Tests\Models\Connective::create([
        'name' => 'name30',
    ]);

    /**
     * @var ConnectiveContract & \AuroraWebSoftware\Connective\Tests\Models\Connective $connective3
     */
    $connective3 = \AuroraWebSoftware\Connective\Tests\Models\Connective::create([
        'name' => 'name31',
    ]);

    $connection = $connective2->connectTo($connective3, 'a');

    expect($connection)
        ->toBeInstanceOf(Connection::class);

});

it('can connectTo a Connective Model and connectedTo and ConnectedFrom models', function () {

    /**
     * @var ConnectiveContract & \AuroraWebSoftware\Connective\Tests\Models\Connective $connective2
     */
    $connective2 = \AuroraWebSoftware\Connective\Tests\Models\Connective::create([
        'name' => 'name40',
    ]);

    /**
     * @var ConnectiveContract & \AuroraWebSoftware\Connective\Tests\Models\Connective $connective3
     */
    $connective3 = \AuroraWebSoftware\Connective\Tests\Models\Connective::create([
        'name' => 'name41',
    ]);

    $connection = $connective2->connectTo($connective3, 'a');

    expect($connection->connectedTo()->name)->toEqual($connective3->name);
    expect($connection->connectedFrom()->name)->toEqual($connective2->name);

});

it('can get connections of a connective model', function () {

    /**
     * @var ConnectiveContract & \AuroraWebSoftware\Connective\Tests\Models\Connective $connective2
     */
    $connective2 = \AuroraWebSoftware\Connective\Tests\Models\Connective::create([
        'name' => 'name50',
    ]);

    /**
     * @var ConnectiveContract & \AuroraWebSoftware\Connective\Tests\Models\Connective $connective3
     */
    $connective3 = \AuroraWebSoftware\Connective\Tests\Models\Connective::create([
        'name' => 'name51',
    ]);

    /**
     * @var ConnectiveContract & \AuroraWebSoftware\Connective\Tests\Models\Connective $connective4
     */
    $connective4 = \AuroraWebSoftware\Connective\Tests\Models\Connective::create([
        'name' => 'name52',
    ]);

    /**
     * @var ConnectiveContract & \AuroraWebSoftware\Connective\Tests\Models\Connective $connective5
     */
    $connective5 = \AuroraWebSoftware\Connective\Tests\Models\Connective::create([
        'name' => 'name53',
    ]);

    $connective2->connectTo($connective3, 'a');
    $connective2->connectTo($connective4, 'a');
    $connective2->connectTo($connective5, 'b');

    // all (without any paramaters)
    expect($connective2->connections())->toHaveCount(3);

    // connection type "a"
    expect($connective2->connections('a'))->toHaveCount(2);

    // connection type "b"
    expect($connective2->connections('b'))->toHaveCount(1);

    // a and b as (with array paramater)
    expect($connective2->connections(['a', 'b']))->toHaveCount(3);

    // by model type
    expect($connective2->connections(modelTypes: \AuroraWebSoftware\Connective\Tests\Models\Connective::class))->toHaveCount(3);

    // dd($connective2->connections());

});

it('can get connectives of a model', function () {

    /**
     * @var ConnectiveContract & \AuroraWebSoftware\Connective\Tests\Models\Connective $connective2
     */
    $connective2 = \AuroraWebSoftware\Connective\Tests\Models\Connective::create([
        'name' => 'name60',
    ]);

    /**
     * @var ConnectiveContract & \AuroraWebSoftware\Connective\Tests\Models\Connective $connective3
     */
    $connective3 = \AuroraWebSoftware\Connective\Tests\Models\Connective::create([
        'name' => 'name61',
    ]);

    /**
     * @var ConnectiveContract & \AuroraWebSoftware\Connective\Tests\Models\Connective $connective4
     */
    $connective4 = \AuroraWebSoftware\Connective\Tests\Models\Connective::create([
        'name' => 'name62',
    ]);

    /**
     * @var ConnectiveContract & \AuroraWebSoftware\Connective\Tests\Models\Connective $connective5
     */
    $connective5 = \AuroraWebSoftware\Connective\Tests\Models\Connective::create([
        'name' => 'name63',
    ]);

    $otherConnective1 = \AuroraWebSoftware\Connective\Tests\Models\OtherConnective::create([
        'name' => 'othername1',
    ]);

    $connective2->connectTo($connective3, 'a');
    $connective2->connectTo($connective4, 'a');
    $connective2->connectTo($connective5, 'b');

    expect($connective2->connectives())
        ->toHaveCount(3)
        ->each->toBeInstanceOf(ConnectiveContract::class);

    expect($connective2->connectives(connectionTypes: 'a'))
        ->toHaveCount(2)
        ->each->toBeInstanceOf(ConnectiveContract::class);

    expect($connective2->connectives(connectionTypes: 'b'))
        ->toHaveCount(1)
        ->each->toBeInstanceOf(ConnectiveContract::class);

    expect($connective2->connectives(connectionTypes: 'b', modelTypes: \AuroraWebSoftware\Connective\Tests\Models\Connective::class))
        ->toHaveCount(1)
        ->each->toBeInstanceOf(ConnectiveContract::class);

    expect($connective2->connectives(connectionTypes: 'b', modelTypes: \AuroraWebSoftware\Connective\Tests\Models\OtherConnective::class))
        ->toHaveCount(0);

    $connective2->connectTo($otherConnective1, 'b');

    expect($connective2->connectives(connectionTypes: 'b', modelTypes: \AuroraWebSoftware\Connective\Tests\Models\OtherConnective::class))
        ->toHaveCount(1);
});

it('can get nested connectives a model', function () {

    /**
     * @var ConnectiveContract & \AuroraWebSoftware\Connective\Tests\Models\Connective $connective2
     */
    $connective2 = \AuroraWebSoftware\Connective\Tests\Models\Connective::create([
        'name' => 'name72',
    ]);

    /**
     * @var ConnectiveContract & \AuroraWebSoftware\Connective\Tests\Models\Connective $connective3
     */
    $connective3 = \AuroraWebSoftware\Connective\Tests\Models\Connective::create([
        'name' => 'name73',
    ]);

    /**
     * @var ConnectiveContract & \AuroraWebSoftware\Connective\Tests\Models\Connective $connective4
     */
    $connective4 = \AuroraWebSoftware\Connective\Tests\Models\Connective::create([
        'name' => 'name74',
    ]);

    /**
     * @var ConnectiveContract & \AuroraWebSoftware\Connective\Tests\Models\Connective $connective5
     */
    $connective5 = \AuroraWebSoftware\Connective\Tests\Models\Connective::create([
        'name' => 'name75',
    ]);

    /**
     * @var ConnectiveContract & \AuroraWebSoftware\Connective\Tests\Models\Connective $otherConnective1
     */
    $otherConnective1 = \AuroraWebSoftware\Connective\Tests\Models\OtherConnective::create([
        'name' => 'othername71',
    ]);

    /**
     * @var ConnectiveContract & \AuroraWebSoftware\Connective\Tests\Models\Connective $otherConnective2
     */
    $otherConnective2 = \AuroraWebSoftware\Connective\Tests\Models\OtherConnective::create([
        'name' => 'othername72',
    ]);

    $connective2->connectTo($connective3, 'a');
    $connective2->connectTo($connective3, 'b');

    $connective3->connectTo($connective4, 'a');
    $connective3->connectTo($connective4, 'b');

    $connective3->connectTo($otherConnective1, 'a');
    $connective3->connectTo($otherConnective2, 'b');

    $connective4->connectTo($connective5, 'a');
    $otherConnective1->connectTo($otherConnective2, 'c');
    $otherConnective2->connectTo($connective2, 'c');

    expect($connective2->connectives()->connectives('a'))->toHaveCount(2);
    expect($connective2->connectives()->connectives(['a', 'b']))->toHaveCount(3);

    expect(
        $connective2->connectives()
            ->connectives(
                ['a', 'b'],
                \AuroraWebSoftware\Connective\Tests\Models\OtherConnective::class))
        ->toHaveCount(2);

    expect(
        $connective2->connectives()
            ->connectives(
                ['a', 'b'],
                [
                    \AuroraWebSoftware\Connective\Tests\Models\Connective::class,
                ]))
        ->toHaveCount(1);

    expect(
        $connective2->connectives()
            ->connectives(
                ['a', 'b'],
                [
                    \AuroraWebSoftware\Connective\Tests\Models\Connective::class,
                    \AuroraWebSoftware\Connective\Tests\Models\OtherConnective::class,
                ]))
        ->toHaveCount(3);

    expect($connective2->connectives()->connectives()->connectives())->toHaveCount(3);

    expect($connective2->connectives()->connectives()->connectives()->connectives())->toHaveCount(2);

    expect($connective2->connectives()->connectives()->connectives()->connectives('d'))->toHaveCount(0);

    expect($connective2->connectives()->connectives()->connectives()->connectives(modelTypes: \AuroraWebSoftware\Connective\Tests\Models\Connective::class))->toHaveCount(2);

    expect($connective2->connectives()->connectives()->connectives()->connectives(modelTypes: \AuroraWebSoftware\Connective\Tests\Models\OtherConnective::class))->toHaveCount(0);

});

// connected to ve coonetedfrom testleri
