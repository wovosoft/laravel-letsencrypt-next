<?php

namespace Wovosoft\LaravelCpanel\Modules;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Wovosoft\LaravelCpanel\CpanelClient;

class Email
{
    /**
     * @throws ConnectionException
     */
    public function addPop(
        string  $email,
        string  $password,
        ?string $domain = null,
    ): PromiseInterface|Response
    {
        return CpanelClient::parse(
            class: self::class,
            function: __FUNCTION__,
            attrs: [
                'email' => $email,
                'password' => $password,
                'domain' => $domain,
            ]
        );
    }

    /**
     * @throws ConnectionException
     */
    public function deletePop(
        string $email
    ): PromiseInterface|Response
    {
        return CpanelClient::parse(
            class: self::class,
            function: __FUNCTION__,
            attrs: [
                'email' => $email
            ]
        );
    }

    /**
     * @throws ConnectionException
     */
    public function listPops(): PromiseInterface|Response
    {
        return CpanelClient::parse(
            class: self::class,
            function: __FUNCTION__,
            attrs: [
                'email' => 'any'
            ]
        );
    }

    /**
     * @throws ConnectionException
     */
    public function addForwarder(
        string $email,
        string $forwardTo
    ): PromiseInterface|Response
    {
        return CpanelClient::parse(
            class: self::class,
            function: __FUNCTION__,
            attrs: [
                'email' => $email,
                'forward_to' => $forwardTo
            ]
        );
    }
}
