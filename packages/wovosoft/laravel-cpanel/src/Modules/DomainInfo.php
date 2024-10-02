<?php

namespace Wovosoft\LaravelCpanel\Modules;

use Illuminate\Http\Client\ConnectionException;
use Wovosoft\LaravelCpanel\CpanelClient;

class DomainInfo
{
    /**
     * @see https://api.docs.cpanel.net/openapi/cpanel/operation/domains_data/
     * @param string<'list'|'hash'> $format
     * @throws ConnectionException
     */
    public function domainsData(string $format)
    {
        $response = CpanelClient::parse(
            class: self::class,
            function: __FUNCTION__,
            attrs: ["format" => $format]
        );

        if ($response->successful()) {
            dd($response->json());
        } else {
            dd($response->body());
        }
    }

    /**
     * @see https://api.docs.cpanel.net/openapi/cpanel/operation/single_domain_data/
     * @throws ConnectionException
     */
    public function singleDomainData(string $domain)
    {
        $response = CpanelClient::parse(
            class: self::class,
            function: __FUNCTION__,
            attrs: ["domain" => $domain]
        );

        if ($response->successful()) {
            dd($response->json());
        } else {
            dd($response->clientError());
        }
    }
}
