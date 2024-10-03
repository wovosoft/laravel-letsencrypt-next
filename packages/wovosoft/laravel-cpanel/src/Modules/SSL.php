<?php

namespace Wovosoft\LaravelCpanel\Modules;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Wovosoft\LaravelCpanel\CpanelClient;

class SSL
{
    /**
     * @param string $domain
     * @param string $cert
     * @param string $key
     * @param string|null $cabundle
     * @return PromiseInterface|Response
     * @throws ConnectionException
     */
    public function installSsl(
        string  $domain,
        string  $cert,
        string  $key,
        ?string $cabundle = null
    )
    {
        return CpanelClient::parse(
            class   : self::class,
            function: __FUNCTION__,
            attrs   : [
                "domain"   => $domain,
                "cert"     => $cert,
                "key"      => $key,
                "cabundle" => $cabundle,
            ]
        );
    }

    /**
     * @param string $domain
     * @return PromiseInterface|Response
     * @throws ConnectionException
     */
    public function deleteSsl(string $domain): PromiseInterface|Response
    {
        return CpanelClient::parse(
            class   : self::class,
            function: __FUNCTION__,
            attrs   : ["domain" => $domain]
        );
    }

    public function listCerts()
    {
        $response = CpanelClient::parse(
            class   : self::class,
            function: __FUNCTION__,
            attrs   : ["domain" => 'any']  //don't understand why without it returns error
        );

        if ($response->successful()) {
            dd($response->json());
        } else {
            dd($response->body());
        }
    }
}
