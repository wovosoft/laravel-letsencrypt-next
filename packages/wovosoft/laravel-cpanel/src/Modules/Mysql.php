<?php

namespace Wovosoft\LaravelCpanel\Modules;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Wovosoft\LaravelCpanel\CpanelClient;

class Mysql
{
    /**
     * @throws ConnectionException
     */
    public function createDatabase(string $name, ?string $charset = null): PromiseInterface|Response
    {
        return CpanelClient::parse(
            class: self::class,
            function: __FUNCTION__,
            attrs: [
                "db" => $name,
                "charset" => $charset
            ]
        );
    }

    /**
     * @throws ConnectionException
     */
    public function deleteDatabase(string $name): PromiseInterface|Response
    {
        return CpanelClient::parse(
            class: self::class,
            function: __FUNCTION__,
            attrs: [
                "db" => $name,
            ]
        );
    }

    /**
     * @throws ConnectionException
     */
    public function createUser(
        string  $name,
        string  $password,
        ?string $domain = null
    ): PromiseInterface|Response
    {
        return CpanelClient::parse(
            class: self::class,
            function: __FUNCTION__,
            attrs: [
                "name" => $name,
                "password" => $password,
                "domain" => $domain
            ]
        );
    }

    /**
     * @throws ConnectionException
     */
    public function setPrivilegesOnDatabase(
        string $user,
        string $db,
        array  $privileges
    ): PromiseInterface|Response
    {
        return CpanelClient::parse(
            class: self::class,
            function: __FUNCTION__,
            attrs: [
                "user" => $user,
                "db" => $db,
                "privileges" => $privileges
            ]
        );
    }
}
