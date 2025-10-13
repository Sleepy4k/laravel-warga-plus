<?php

namespace App\Services\Install;

use App\Contracts\Models\UserInterface;
use App\Foundations\Service;
use Symfony\Component\Process\PhpExecutableFinder;

class FinishedService extends Service
{
    /**
     * Create a new service instance.
     *
     * @param UserInterface $userInterface
     */
    public function __construct(private UserInterface $userInterface) {}

    /**
     * Handle the incoming request.
     *
     * @return array
     */
    public function invoke(): array
    {
        try {
            $appName = config('app.name');
            $base_url = url('/');
            $base_path = base_path();
            $phpFinder = new PhpExecutableFinder;
            $execPath = $phpFinder->find(false) ?? "php";
            $minPhpVersion = config('installer.core.minPhpVersion');
            $username = $this->userInterface->get(['id'], true, ['personal:id,user_id,first_name,last_name'])->full_name;

            return compact('base_url', 'base_path', 'execPath', 'minPhpVersion', 'username', 'appName');
        } catch (\Exception $e) {
            throw new \Exception('Could not finished installation: '.$e->getMessage());
        }
    }
}
