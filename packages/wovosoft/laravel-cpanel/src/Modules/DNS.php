<?php

namespace Wovosoft\LaravelCpanel\Modules;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Wovosoft\LaravelCpanel\CpanelClient;

class DNS
{
    /**
     * @throws ConnectionException
     */
    public function addZoneRecord(
        string  $domain,
        string  $type,
        string  $name,
        string  $data,
        ?string $ttl = null,
    ): PromiseInterface|Response
    {
        return CpanelClient::parse(
            class: self::class,
            function: __FUNCTION__,
            attrs: [
                'domain' => $domain,
                'type' => $type,
                'name' => $name,
                'data' => $data,
                'ttl' => $ttl,
            ]
        );
    }

    /**
     * @throws ConnectionException
     */
    public function removeZoneRecord(
        string $domain,
        string $id
    ): PromiseInterface|Response
    {
        return CpanelClient::parse(
            class: self::class,
            function: __FUNCTION__,
            attrs: [
                'domain' => $domain,
                'id' => $id,
            ]
        );
    }

    /**
     * @see https://api.docs.cpanel.net/openapi/cpanel/operation/ensure_domains_reside_only_locally/
     * @throws ConnectionException
     */
    public function ensureDomainsResideOnlyLocally(
        string $domain
    ): PromiseInterface|Response
    {
        return CpanelClient::parse(
            class: self::class,
            function: __FUNCTION__,
            attrs: [
                'domain' => $domain
            ]
        );
    }

    /**
     * @see https://api.docs.cpanel.net/openapi/cpanel/operation/has_local_authority/
     * @throws ConnectionException
     */
    public function hasLocalAuthority(
        string $domain
    ): PromiseInterface|Response
    {
        return CpanelClient::parse(
            class: self::class,
            function: __FUNCTION__,
            attrs: [
                'domain' => $domain
            ]
        );
    }

    /**
     * @see https://api.docs.cpanel.net/openapi/cpanel/operation/DNS::is_alias_available/
     * @return PromiseInterface|Response
     * @throws ConnectionException
     */
    public function isAliasAvailable(): PromiseInterface|Response
    {
        return CpanelClient::parse(
            class: self::class,
            function: __FUNCTION__,
            attrs: [
                'domain' => 'any'
            ]
        );
    }

    /**
     * @see https://api.docs.cpanel.net/openapi/cpanel/operation/dns-lookup/
     * @param string $domain
     * @return PromiseInterface|Response
     * @throws ConnectionException
     */
    public function lookup(
        string $domain
    ): PromiseInterface|Response
    {
        return CpanelClient::parse(
            class: self::class,
            function: __FUNCTION__,
            attrs: [
                'domain' => $domain
            ]
        );
    }


    /**
     * @see https://api.docs.cpanel.net/openapi/cpanel/operation/dns-mass_edit_zone/
     * @param string $zone
     * @param string $serial
     * @param array{
     *     'dname':string,
     *     'ttl':string,
     *     'record_type':string,
     *     'data':string[]
     * } $data
     * @return PromiseInterface|Response
     * @throws ConnectionException
     */
    public function addZone(
        string $zone,
        string $serial,
        array  $data
    ): PromiseInterface|Response
    {
        return CpanelClient::parse(
            class: self::class,
            function: 'mass_edit_zone',
            attrs: [
                'zone' => $zone,
                'serial' => $serial,
                'add' => json_encode($data),
            ]
        );
    }

    /**
     * @see https://api.docs.cpanel.net/openapi/cpanel/operation/dns-mass_edit_zone/
     * @param string $zone
     * @param string $serial
     * @param array{
     *     'line_index':numeric,
     *     'dname':string,
     *     'ttl':string,
     *     'record_type':string,
     *     'data':string[]
     * } $data
     * @return PromiseInterface|Response
     * @throws ConnectionException
     */
    public function editZone(
        string $zone,
        string $serial,
        array  $data
    ): PromiseInterface|Response
    {
        return CpanelClient::parse(
            class: self::class,
            function: 'mass_edit_zone',
            attrs: [
                'zone' => $zone,
                'serial' => $serial,
                'edit' => json_encode($data),
            ]
        );
    }

    /**
     * @see https://api.docs.cpanel.net/openapi/cpanel/operation/dns-mass_edit_zone/
     * @param int $lineIndex
     * @return PromiseInterface|Response
     * @throws ConnectionException
     */
    public function removeZone(
        int $lineIndex
    ): PromiseInterface|Response
    {
        return CpanelClient::parse(
            class: self::class,
            function: 'mass_edit_zone',
            attrs: [
                'remove' => $lineIndex
            ]
        );
    }

    /**
     * @see https://api.docs.cpanel.net/openapi/cpanel/operation/dns-parse_zone/
     * @param string $zone
     * @return PromiseInterface|Response
     * @throws ConnectionException
     */
    public function parseZone(
        string $zone
    ): PromiseInterface|Response
    {
        return CpanelClient::parse(
            class: self::class,
            function: __FUNCTION__,
            attrs: [
                'zone' => $zone
            ]
        );
    }

    /**
     * @param string $domain
     * @param string $sourceIp
     * @param string|null $destIp
     * @param string|null $ftpIp
     * @return PromiseInterface|Response
     * @throws ConnectionException
     */
    public function swapIpInZones(
        string  $domain,
        string  $sourceIp,
        ?string $destIp,
        ?string $ftpIp = null,
    ): PromiseInterface|Response
    {
        return CpanelClient::parse(
            class: self::class,
            function: __FUNCTION__,
            attrs: [
                'domain' => $domain,
                'source_ip' => $sourceIp,
                'dest_ip' => $destIp,
                'ftp_ip' => $ftpIp,
            ]
        );
    }
}
