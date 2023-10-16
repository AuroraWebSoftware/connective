<?php

namespace AuroraWebSoftware\Connective\Tests;

use AuroraWebSoftware\Connective\ConnectiveServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'AuroraWebSoftware\\Connective\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            ConnectiveServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_connective_table.php.stub';
        $migration->up();
        */

        // for GitHub tests wirh mysql
        // config()->set('database.default', 'mysql');

        // for local tests with sqlite
        config()->set('database.default', 'testing');

        // for local tests with mysql
        config()->set('database.default', 'mysql');
    }
}
