<?php

namespace App\Helpers;

class SslApplication
{
    private string $domain;
    private string $email;
    private string $method;
    private string $httpVerificationFileStorePath;
    private string $sslStorePath;


    public static function init(array $params): static
    {
        return (new static())->setParams($params);
    }

    public function setParams(array $params): static
    {
        $this->domain                        = data_get($params, 'domain');
        $this->email                         = data_get($params, 'email');
        $this->method                        = data_get($params, 'method');
        $this->httpVerificationFileStorePath = data_get($params, 'http_verification_file_store_path');
        $this->sslStorePath                  = data_get($params, 'ssl_store_path');
        return $this;
    }

    /**
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * @param string $domain
     * @return SslApplication
     */
    public function setDomain(string $domain): SslApplication
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return SslApplication
     */
    public function setEmail(string $email): SslApplication
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @return SslApplication
     */
    public function setMethod(string $method): SslApplication
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @return string
     */
    public function getHttpVerificationFileStorePath(): string
    {
        return $this->httpVerificationFileStorePath;
    }

    /**
     * @param string $httpVerificationFileStorePath
     * @return SslApplication
     */
    public function setHttpVerificationFileStorePath(string $httpVerificationFileStorePath): SslApplication
    {
        $this->httpVerificationFileStorePath = $httpVerificationFileStorePath;
        return $this;
    }

    /**
     * @return string
     */
    public function getSslStorePath(): string
    {
        return $this->sslStorePath;
    }

    /**
     * @param string $sslStorePath
     * @return SslApplication
     */
    public function setSslStorePath(string $sslStorePath): SslApplication
    {
        $this->sslStorePath = $sslStorePath;
        return $this;
    }

}
