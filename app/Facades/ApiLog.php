<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class ApiLog
 * @package App\Facades
 *
 * @mixin \App\Services\Logs\ApiLogService
 */
class ApiLog extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'api_log';
    }
}
