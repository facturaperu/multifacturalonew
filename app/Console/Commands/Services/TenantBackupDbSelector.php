<?php

namespace App\Console\Commands\Services;

use Hyn\Tenancy\Models\Website;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Spatie\Backup\Tasks\Backup\DbDumperFactory;
use Spatie\DbDumper\Databases\MySql;
use Spatie\DbDumper\DbDumper;

class TenantBackupDbSelector extends DbDumperFactory
{
    public static function getTenantDatabaseConnections(): Collection
    {
        $websites = Website::all();

        return $websites->map(function ($tenant) {
            return self::createConnection($tenant->uuid);
        });
    }

    public static function createConnection(string $tenantDbName): DbDumper
    {
        $dbConfig = config('database.connections.' . config('tenancy.db.system-connection-name', 'system'));

        $dbDumper = static::forDriver($dbConfig['driver'])
            ->setHost(Arr::first(Arr::wrap($dbConfig['host'] ?? '')))
            ->setDbName($tenantDbName)
            ->setUserName($dbConfig['username'] ?? '')
            ->setPassword($dbConfig['password'] ?? '');
        if ($dbDumper instanceof MySql) {
            $dbDumper->setDefaultCharacterSet($dbConfig['charset'] ?? '');
        }
        if (isset($dbConfig['port'])) {
            $dbDumper = $dbDumper->setPort($dbConfig['port']);
        }
        if (isset($dbConfig['dump'])) {
            $dbDumper = static::processExtraDumpParameters($dbConfig['dump'], $dbDumper);
        }

        return $dbDumper;
    }
}
