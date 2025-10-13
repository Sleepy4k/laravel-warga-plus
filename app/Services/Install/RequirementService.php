<?php

namespace App\Services\Install;

use App\Foundations\Service;
use App\Support\RequirementsChecker;

class RequirementService extends Service
{
    /**
     * Create a new service instance.
     *
     * @param RequirementsChecker $checker
     */
    public function __construct(private RequirementsChecker $checker) {}

    /**
     * Handle the incoming request.
     *
     * @return array
     */
    public function invoke(): array
    {
        try {
            $requirements = $this->checker->check();
            $php = $this->checker->checkPHPversion();

            return compact('php', 'requirements');
        } catch (\Exception $e) {
            throw new \Exception('Could not check requirements: '.$e->getMessage());
        }
    }
}
