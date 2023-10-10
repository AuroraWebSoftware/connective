<?php

namespace AuroraWebSoftware\Connective;

use AuroraWebSoftware\Connective\Commands\ConnectiveCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ConnectiveServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('connective')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_connective_table')
            ->hasCommand(ConnectiveCommand::class);
    }
}
