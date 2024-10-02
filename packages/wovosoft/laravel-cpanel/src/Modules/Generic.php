<?php

namespace Wovosoft\LaravelCpanel\Modules;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Wovosoft\LaravelCpanel\CpanelClient;

class Generic
{
    /**
     * @param string $module
     * @param string $function
     * @param array $params
     * @return PromiseInterface|Response
     * @throws ConnectionException
     */
    public function uapi(
        string $module,
        string $function,
        array  $params = [],
    ): PromiseInterface|Response
    {
        return CpanelClient::query($module, $function, $params);
    }

}
