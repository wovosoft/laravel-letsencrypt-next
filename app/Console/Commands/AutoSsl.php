<?php

namespace App\Console\Commands;

use App\Helpers\SslApplication;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Wovosoft\LaravelCpanel\Modules\FileMan;
use Wovosoft\LaravelCpanel\Modules\SSL;
use Wovosoft\LaravelLetsencryptCore\Client;
use Wovosoft\LaravelLetsencryptCore\Data\Authorization;
use Wovosoft\LaravelLetsencryptCore\Data\Order;
use Wovosoft\LaravelLetsencryptCore\Enums\Modes;
use function Illuminate\Filesystem\join_paths;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\outro;
use function Laravel\Prompts\select;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\table;
use function Laravel\Prompts\text;

class AutoSsl extends Command
{
    private Client $client;
    /**
     * @var Collection<int,Authorization>
     */
    private Collection $authorizations;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-ssl';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatic SSL Generation from LetsEncrypt';

    private array          $domains;
    private Order          $order;
    private array          $selfTestCases = [];
    private SslApplication $application;

    /**
     * Execute the console command.
     * @throws Exception
     */
    public function handle()
    {
        //select application
        $applications = config("ssl.applications");
        $application  = select(
            label   : 'Select Application',
            options : array_keys($applications),
            required: true
        );

        $this->application = SslApplication::init($applications[$application]);

        $this->process();
    }

    /**
     * @throws Exception
     */
    private function process()
    {
        $this
            ->createClient()
            ->createOrderAndSetAuthorizations()
            ->displayDomainAuthorizationChallenges()
            ->verifyConfigurationAndGenerateCertificates();
    }

    /**
     * Self test
     * @return void
     * @throws Exception
     */
    private function verifyConfigurationAndGenerateCertificates()
    {
        outro("Ownership Verification Configuration");

        $this->info("With DNS validation, after the selfTest has confirmed that DNS has been updated, it is recommended
        you wait some additional time before proceeding, e.g. sleep(30);. This is because Let’s Encrypt will perform multiple
        viewpoint validation, and your DNS provider may not have completed propagating the changes across their network.
        If you proceed too soon, Let's Encrypt will fail to validate.");

        $case = $this->isDefaultSkipped()
            ? Client::VALIDATION_HTTP
            : select(
                label   : 'Select Test Case',
                options : array_keys($this->selfTestCases),
                required: true
            );

        $result = spin(
            callback: function () use ($case) {
                return $this->client->selfTest(
                    authorization: $this->selfTestCases[$case],
                    type         : $case,
                );
            },
            message : 'Verifying Your Configuration',
        );

        if (!$result) {
            $this->error("Could not locally verify ownership via $case");
        } else {
            $this->info("Your configuration has been verified locally via $case");

            $isValidatedOnline = spin(
                callback: fn() => $this->validateOnline($this->selfTestCases[$case], $case),
                message : "Validating Online..."
            );

            if ($isValidatedOnline) {
                $this->info("Your configuration has been verified online via $case");
                $this->getCertificates();
            } else {
                $this->error("Unable to validate online");
            }
        }
    }

    private function installCertificates(array $certificates)
    {
        if ($this->application->shouldUpdateSsl()) {

            $response = spin(
                callback: function () use ($certificates) {
                    $sslManager = new SSL();
                    return $sslManager->deleteSsl(
                        domain: $this->application->getDomain()
                    );
                },
                message : "Deleting Previous Certificates..."
            );

            if ($response->failed()) {
                dd($response->json());
            }

            $response = spin(
                callback: function () use ($certificates) {
                    $sslManager = new SSL();
                    return $sslManager->installSsl(
                        domain: $this->application->getDomain(),
                        cert  : $certificates['certificate.cert'],
                        key   : $certificates['private.key'],
                    );
                },
                message : "Updating SSL Certificates..."
            );

            if ($response->successful()) {
                $this->info("SSL Certificates Updated");
                dump($response->json());
            } else {
                dd($response->json());
            }

            exit(0);
        }
        return false;
    }

    /**
     * @throws Exception
     */
    private function getCertificates()
    {
        $isReady = spin(
            callback: fn() => $this->client->isReady($this->order),
            message : "Checking Order..."
        );

        if ($isReady) {
            try {
                $certificates = spin(
                    callback: function () {
                        $certificate = $this->client->getCertificate($this->order);

                        return [
                            "certificate.cert"         => $certificate->getCertificate(),
                            "private.key"              => $certificate->getPrivateKey(),
                            "domain_certificate"       => $certificate->getCertificate(false),
                            "intermediate_certificate" => $certificate->getIntermediate(),
                        ];
                    },
                    message : "Generating SSL Certificates..."
                );

                $this->installCertificates($certificates);
            } catch (Exception $exception) {
                dd($exception->getMessage());
            }


            File::ensureDirectoryExists($this->application->getSslStorePath());

            foreach ($certificates as $name => $content) {
                $path = join_paths($this->application->getSslStorePath(), $name);
                File::put($path, $content);
                $this->info("$path Generated");
            }

            return $certificates;
        }
        $this->error("Order is not ready");
        exit();
    }

    /**
     * @throws Exception
     */
    private function validateOnline(Authorization $authorization, string $type): bool|string
    {
        if ($type == Client::VALIDATION_HTTP) {
            return $this->client->validate($authorization->getHttpChallenge());
        } elseif ($type == Client::VALIDATION_DNS) {
            return $this->client->validate($authorization->getDnsChallenge());
        }
        return false;
    }

    /**
     * @throws ConnectionException
     */
    private function displayDomainAuthorizationChallenges(): static
    {
        foreach ($this->authorizations as $authorization) {
            //push test cases
            $this->selfTestCases[Client::VALIDATION_HTTP] = $authorization;
            $this->selfTestCases[Client::VALIDATION_DNS]  = $authorization;


            outro("HTTP Authorization for {$authorization->getDomain()}");
            $this->info(
                '1. Create a folder ".well-known" in the root folder'
                . ' of your domain. And inside the ".well-known" create'
                . ' another folder "acme-challenge". Then upload the'
                . ' above file(s) inside the acme-challenge folder.'
            );

            $file = $authorization->getFile();

            $this->info("2. File Should be accessible at \"{$authorization->getDomain()}/.well-known/acme-challenge/{$file->getFilename()}\"");


            table(
                headers: ['File Name', 'File Content'],
                rows   : [
                    [
                        $file->getFilename(),
                        $file->getContents()
                    ]
                ]
            );

            if ($txtRecord = $authorization->getTxtRecord()) {
                outro("DNS Authorization for {$authorization->getDomain()}");
                $this->showDnsAuthorizationHints();
                table(
                    headers: ['Record Name', 'Record Value'],
                    rows   : [
                        [
                            $txtRecord?->getName(),
                            $txtRecord?->getValue()
                        ]
                    ]
                );
            }


            $shouldStoreFile = $this->isDefaultSkipped() || confirm(label: 'Store HTTP Verification File?');

            if ($this->application->shouldUploadToHost()) {
                $temporaryStorePath = join_paths("autossl", $this->application->getDomain(), $file->getFilename());

                File::ensureDirectoryExists(dirname(Storage::path($temporaryStorePath)));

                if (!Storage::put($temporaryStorePath, $file->getContents())) {
                    $this->error("Unable to store file at: $temporaryStorePath");
                    exit();
                }

                $fileManager = new FileMan();
                $response    = $fileManager->uploadFile(
                    filePath       : Storage::path($temporaryStorePath),
                    destinationPath: $this->application->getHttpVerificationFileStorePath()
                );

                if ($response->failed()) {
                    dd($response->json());
                }
            } elseif ($shouldStoreFile) {
                $filePath = join_paths($this->application->getHttpVerificationFileStorePath(), $file->getFilename());

                File::ensureDirectoryExists(dirname($filePath));

                if (!$filePath) {
                    $this->error("File Path is required. Please set verification_file_storage_directory in config file.");
                    exit();
                }

                File::put($filePath, $file->getContents());
                $this->info("Verification File Store at: $filePath");

            }
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    private function createOrderAndSetAuthorizations(): static
    {
        $domains = $this->isDefaultSkipped()
            ? $this->application->getDomain()
            : text(
                label      : "Domains",
                placeholder: "Write down the domains",
                default    : $this->application->getDomain(),
                required   : true,
                hint       : "Domains Comma Separated",
            );

        $this->domains = str($domains)->explode(",")->toArray();

        spin(
            callback: function () {
                $this->order = $this->client->createOrder(
                    domains: $this->domains,
                );
            },
            message : 'Creating Order...'
        );

        spin(
            callback: function () {
                $this->authorizations = $this->client->authorize($this->order);
            },
            message : 'Fetching Authorizations...'
        );


        return $this;
    }

    private function isDefaultSkipped(): bool
    {
        return config("ssl.skip_showing_defaults", false);
    }

    /**
     * @throws Exception
     */
    private function createClient(): static
    {
        $mode = select(
            label   : 'Select Mode',
            options : [
                Modes::Staging->value => Modes::Staging->name,
                Modes::Live->value    => Modes::Live->name,
            ],
            default : $this->application->getMethod(),
            required: true,
        );

        $email = $this->isDefaultSkipped()
            ? $this->application->getEmail()
            : text(
                label      : "Email",
                placeholder: "Enter Email",
                default    : $this->application->getEmail(),
                required   : true,
                hint       : "Let's Encrypt Email Address",
            );

        spin(
            callback: function () use ($email, $mode) {
                $this->client = new Client(
                    mode    : Modes::tryFrom($mode),
                    username: $email,
                );
            },
            message : 'Creating/Fetching Client Information...'
        );

        return $this;
    }


    private function showDnsAuthorizationHints(): void
    {
        $lines = [
            '1. Login to your domain host (or wherever service that is "in control" of your domain).',
            '2. Go to the DNS record settings and create a new TXT record.',
            '3. In the Name/Host/Alias field, enter the domain TXT record from below table for example: "_acme-challenge".',
            '4. In the Value/Answer field enter the verfication code from below table.',
            '5. Wait for few minutes for the TXT record to propagate. You can check if it worked by clicking on the "Check DNS" button. If you have multiple entries, make sure all of them are ok.',
        ];

        foreach ($lines as $line) {
            $this->info($line);
        }
    }
}
