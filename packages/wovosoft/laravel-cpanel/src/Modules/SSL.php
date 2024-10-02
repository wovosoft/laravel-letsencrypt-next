<?php

namespace Wovosoft\LaravelCpanel\Modules;

use Illuminate\Http\Client\ConnectionException;
use Wovosoft\LaravelCpanel\CpanelClient;

class SSL
{
    /**
     * @throws ConnectionException
     */
    public function installSsl(
        string $domain,
        string $cert,
        string $key,
        string $cabundle
    )
    {
        $response = CpanelClient::parse(
            class: self::class,
            function: __FUNCTION__,
            attrs: [
                "domain" => $domain,
                "cert" => $cert,
                "key" => $key,
                "cabundle" => $cabundle,
            ]
        );

        if ($response->successful()) {
            dd($response->json());
        } else {
            dd($response->body());
        }
    }

    /**
     * @throws ConnectionException
     */
    public function deleteSsl(string $domain)
    {
        $response = CpanelClient::parse(
            class: self::class,
            function: __FUNCTION__,
            attrs: ["domain" => $domain]
        );

        if ($response->successful()) {
            dd($response->json());
        } else {
            dd($response->body());
        }
    }

    public function listCerts()
    {
        $response = CpanelClient::parse(
            class: self::class,
            function: __FUNCTION__,
            attrs: ["domain" => 'any']  //don't understand why without it returns error
        );

        if ($response->successful()) {
            dd($response->json());
        } else {
            dd($response->body());
        }
    }
}
