<?php

namespace App\Services\Install;

use App\Foundations\Service;
use App\Support\Environment;
use App\Support\EnvironmentManager;
use App\Support\PrivilegesChecker;
use App\Traits\DatabaseTest;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;

class SetupService extends Service
{
    use DatabaseTest;

    /**
     * Create a new service instance.
     *
     * @param EnvironmentManager $environmentManager
     */
    public function __construct(private EnvironmentManager $environmentManager) {}

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index(): array
    {
        try {
            $guessedUrl = EnvironmentManager::guestUrl();
            $defaultConfig = [
                'app_name' => config('app.name'),
                'db_host' => config('database.connections.mysql.host'),
                'db_port' => config('database.connections.mysql.port'),
                'db_name' => config('database.connections.mysql.database'),
                'db_user' => config('database.connections.mysql.username'),
            ];

            return compact('guessedUrl', 'defaultConfig');
        } catch (\Exception $e) {
            throw new \Exception('Could not get the default configuration: '.$e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param array $request
     *
     * @return void
     */
    public function store(array $request): void
    {
        try {
            Artisan::call('config:clear');
            $connection = $this->testDatabaseConnection($request);
            (new PrivilegesChecker($connection))->check();
        } catch (\Exception $e) {
            $validator = Validator::make([], []);
            $this->setDatabaseTestsErrors($validator, $e);

            // Throw an exception with the validation errors
            throw new \Exception('Could not connect to the database: '.$e->getMessage());
        }

        if (!$this->environmentManager->saveEnvFile(
            new Environment(
                name: $request['app_name'],
                key: config('app.key'),
                url: $request['app_url'],
                dbHost: $request['db_host'],
                dbPort: $request['db_port'],
                dbName: $request['db_name'],
                dbUser: $request['db_user'],
                dbPassword: $request['db_pass'] ?: '',
            )
        )) throw new \Exception('Failed to write .env file, make sure that the files permissions and ownership are correct. Check documentation on how to setup the permissions and ownership.');
    }
}
