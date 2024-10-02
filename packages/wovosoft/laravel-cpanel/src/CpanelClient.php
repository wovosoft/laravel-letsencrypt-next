<?php

namespace Wovosoft\LaravelCpanel;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class CpanelClient
{
    private string $domain;
    private string $apiToken;
    private string $username;
    private string $port;

    private PendingRequest $http;

    private array $cpanelUapiModules = [
        'Mysql' => [
            'create_database' => [
                'db' => 'Database name',
                'charset' => 'Character set (optional)',
            ],
            'delete_database' => [
                'db' => 'Database name',
            ],
            'create_user' => [
                'name' => 'Username',
                'password' => 'Password',
                'domain' => 'Domain (optional)',
            ],
            'set_privileges_on_database' => [
                'user' => 'Username',
                'db' => 'Database name',
                'privileges' => 'Array of privileges',
            ],
        ],
        'Email' => [
            'add_pop' => [
                'email' => 'Email address',
                'password' => 'Password',
                'domain' => 'Domain (optional)',
            ],
            'delete_pop' => [
                'email' => 'Email address',
            ],
            'list_pops' => [],
            'add_forwarder' => [
                'email' => 'Email address',
                'forward_to' => 'Forwarding address',
            ],
        ],
        'SSL' => [
            'install_ssl' => [
                'domain' => 'Domain name',
                'cert' => 'SSL certificate',
                'key' => 'SSL key',
                'cabundle' => 'CA bundle (optional)',
            ],
            'list_certs' => [],
            'delete_ssl' => [
                'domain' => 'Domain name',
            ],
        ],
        'DomainInfo' => [
            'domains_data' => [],
            'single_domain_data' => [
                'domain' => 'Domain name',
            ],
        ],
        'DNS' => [
            'add_zone_record' => [
                'domain' => 'Domain name',
                'type' => 'Record type (e.g., A, CNAME)',
                'name' => 'Record name',
                'data' => 'Record data',
                'ttl' => 'TTL (optional)',
            ],
            'remove_zone_record' => [
                'domain' => 'Domain name',
                'id' => 'Record ID',
            ],
            'fetch_zone_records' => [
                'domain' => 'Domain name',
            ],
        ],
        'Fileman' => [
            'upload_file' => [
                'path' => 'Destination path',
                'file' => 'File data',
            ],
            'delete_files' => [
                'files' => 'Array of file paths',
            ],
            'list_files' => [
                'path' => 'Directory path',
            ],
        ],
        'Ftp' => [
            'add_ftp' => [
                'user' => 'Username',
                'pass' => 'Password',
                'quota' => 'Quota (optional)',
                'domain' => 'Domain (optional)',
            ],
            'delete_ftp' => [
                'user' => 'Username',
            ],
            'list_ftp' => [],
        ],
        'Bandwidth' => [
            'get_stats' => [
                'domain' => 'Domain name (optional)',
            ],
            'get_bandwidth' => [],
        ],
        'Backup' => [
            'fullbackup' => [],
            'list_backups' => [],
        ],
        'Preferences' => [
            'change_password' => [
                'password' => 'New password',
                'oldpassword' => 'Old password (optional)',
            ],
            'set_locale' => [
                'locale' => 'Locale string',
            ],
            'list_locales' => [],
        ],
    ];


    public static function init(): static
    {
        return (new self)
            ->setDomain(config('laravel-cpanel.cpanel.hostname'))
            ->setApiToken(config('laravel-cpanel.cpanel.api_token'))
            ->setUsername(config('laravel-cpanel.cpanel.username'))
            ->setPort(config('laravel-cpanel.cpanel.port'));
    }

    public function getHttpClient(): PendingRequest
    {
        return $this->http;
    }

    public static function client(): static
    {
        $instance = self::init();

        // Set headers
        $headers = [
            "Authorization" => "cpanel " . $instance->getUsername() . ':' . $instance->getApiToken(),
            "cache-control" => "no-cache"
        ];

        $instance->http = Http::withHeaders($headers)
            ->baseUrl($instance->getDomain() . ":" . $instance->getPort() . "/execute")
            ->withOptions([
                'verify' => false, // Disables SSL verification
            ]);

        return $instance;
    }

    /**
     * @throws ConnectionException
     */
    public static function parse(string $class, string $function, array $attrs = []): PromiseInterface|Response
    {
        return self::client()->query(
            module: class_basename($class),
            function: str($function)->snake()->value(),
            attrs: $attrs
        );
    }

    /**
     * @throws ConnectionException
     */
    public function query(string $module, string $function, array $attrs = []): PromiseInterface|Response
    {
        if (empty($attrs)) {
            return $this->http->post("$module/$function");
        }
        return $this->http->post("$module/$function", $attrs);
    }

    /**
     * @throws ConnectionException
     */
    public function get(string $module, string $function, array $attrs = []): PromiseInterface|Response
    {
        return $this->http->get("$module/$function", $attrs);
    }

    /**
     * @throws ConnectionException
     */
    public function post(string $module, string $function, array $attrs = []): PromiseInterface|Response
    {
        return $this->http->post("$module/$function", $attrs);
    }


    // Getter for domain
    public function getDomain(): string
    {
        return $this->domain;
    }

    // Setter for domain
    public function setDomain(string $domain): static
    {
        $this->domain = $domain;
        return $this;
    }

    // Getter for apiToken
    public function getApiToken(): string
    {
        return $this->apiToken;
    }

    // Setter for apiToken
    public function setApiToken(string $apiToken): static
    {
        $this->apiToken = $apiToken;
        return $this;
    }

    // Getter for username
    public function getUsername(): string
    {
        return $this->username;
    }

    // Setter for username
    public function setUsername(string $username): static
    {
        $this->username = $username;
        return $this;
    }

    // Getter for port
    public function getPort(): string
    {
        return $this->port;
    }

    // Setter for port
    public function setPort(string $port): static
    {
        $this->port = $port;
        return $this;
    }


}
