<?php

namespace Wovosoft\LaravelCpanel\Modules;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Wovosoft\LaravelCpanel\CpanelClient;

class DomainInfo
{
    /**
     * @see https://api.docs.cpanel.net/openapi/cpanel/operation/domains_data/
     * @param string<'list'|'hash'> $format
     * @return PromiseInterface|Response
     * @throws ConnectionException
     */
    public function domainsData(string $format): PromiseInterface|Response
    {
        return CpanelClient::parse(
            class   : self::class,
            function: __FUNCTION__,
            attrs   : ["format" => $format]
        );
    }

    /**
     * @see https://api.docs.cpanel.net/openapi/cpanel/operation/single_domain_data/
     * @param string $domain
     * @return PromiseInterface|Response
     * @throws ConnectionException
     */
    public function singleDomainData(string $domain): PromiseInterface|Response
    {
        return CpanelClient::parse(
            class   : self::class,
            function: __FUNCTION__,
            attrs   : ["domain" => $domain]
        );
    }
}
