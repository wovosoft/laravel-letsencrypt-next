<?php

namespace Wovosoft\LaravelCpanel\Modules;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Wovosoft\LaravelCpanel\CpanelClient;

class SubDomain
{
    public function addsubdomain(
        string $domain,
        string $rootdomain,
        string $dir,
        int    $canOff = 1,
        int    $disallowDot = 0
    ): PromiseInterface|Response
    {
        return CpanelClient::parse(
            class: self::class,
            function: __FUNCTION__,
            attrs: [
                'domain' => $domain,
                'rootdomain' => $rootdomain,
                'dir' => $dir,
                'canoff' => $canOff,
                'disallowdot' => $disallowDot
            ]
        );
    }
}
