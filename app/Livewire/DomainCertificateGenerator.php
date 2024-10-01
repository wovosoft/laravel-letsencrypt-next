<?php

namespace App\Livewire;

use App\Models\Domain;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Wovosoft\LaravelLetsencryptCore\Client;
use Wovosoft\LaravelLetsencryptCore\Data\Authorization;
use Wovosoft\LaravelLetsencryptCore\Data\Order;
use Wovosoft\LaravelLetsencryptCore\Enums\Modes;
use function Illuminate\Filesystem\join_paths;

class DomainCertificateGenerator extends Component
{
    public Domain $domain;
    public bool $showFirstStep = true;
    public bool $showSecondStep = false;
    public bool $showThirdStep = false;
    public ?int $orderId = null;
    public array $certificates = [];

    private Client $client;
    private Order $order;
    private $mode = Modes::Staging;

    private ?string $httpVerificationFile = null;

    /**
     * @var Collection<int,Authorization>
     */
    private Collection $authorizations;

    public function mount(Domain $domain): void
    {
        $this->domain = $domain;
    }

    /**
     * @throws Exception|GuzzleException
     */
    public function handleFirstStep(): void
    {
        try {
            $this
                ->createClient($this->domain->email)
                ->createOrderAndSetAuthorizations($this->domain->domain)
                ->displayDomainAuthorizationChallenges();

            if ($this->uploadHttpVerificationFile()) {
                $this->verifyConfigurationAndGenerateCertificates();
            }

        } catch (\Exception $exception) {
            dump($exception->getMessage());
        }

    }


    /**
     * @throws Exception
     */
    private function createClient(string $email): static
    {
        $this->client = new Client(
            mode: $this->mode,
            username: $email,
        );

        return $this;
    }

    /**
     * @throws Exception
     */
    private function createOrderAndSetAuthorizations(string $domains): static
    {
        $this->order = $this->client->createOrder(
            domains: str($domains)->explode(",")->toArray(),
        );

        //set order id for next step ' s use
        $this->orderId = $this->order->getId();

        $this->authorizations = $this->client->authorize($this->order);

        return $this;
    }

    private function displayDomainAuthorizationChallenges(): static
    {
        foreach ($this->authorizations as $authorization) {
            $file = $authorization->getFile();

            $storeFilePath = "registered-users-ssl-verification/{$file->getFilename()}";

            Storage::put($storeFilePath, $file->getContents());

            $this->httpVerificationFile = $storeFilePath;
        }

        return $this;
    }

    /**
     * @throws ConnectionException
     */
    private function uploadHttpVerificationFile(): bool
    {
        /**
         * For remote servers, file will be sent over http, the remote server should handle how to store it
         * This unsecured route (http) should be allowed, in proxy servers.
         * Because, domain ownership verification will be done using this url.
         * in http://domainame.com/.well-known/acme-challenge/{filename}
         * verification_file_upload_params = {keys used to authorize the request}
         * please take proper validation actions before accepting and storing the file.
         */
        if ($this->isUrl($this->httpVerificationFile)) {
            $response = Http::attach(
                'verification_file',           // The key to use in the request
                Storage::get($this->httpVerificationFile),
                basename($this->httpVerificationFile)
            )->post($this->domain->verification_file_upload_url, $this->domain->verification_file_upload_params);

            if ($response->successful()) {
                Storage::delete($this->httpVerificationFile);
                return true;
            }

            return false;
        }

        /**
         * N.B.: This operation needs required permissions.
         * On same location, just move the file to the correct location.
         * i.e. /root/public_html/laravel_application/public/.well-known/acme-challenge/{file_name}
         * verification_file_upload_url = /root/public_html/laravel_application/public/.well-known/acme-challenge
         * file name will be set dynamically
         */
        return File::move(
            Storage::path($this->httpVerificationFile),
            join_paths($this->domain->verification_file_upload_url, basename($this->httpVerificationFile))
        );
    }

    private function isUrl($string): bool
    {
        return filter_var($string, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * First verify domain ownership locally by the application.
     * Then verify via Let's Encrypt,
     * if verification is successful, it will render the certificates obtained
     * from Let's Encrypt
     *
     * Note: Because, livewire doesn't store private variables between requests,
     * and making public these variables (Let's Encrypt related variables)
     * should not be done, so we need to fetch client and order again.
     * @return void
     * @throws GuzzleException
     * @throws Exception
     */
    private function verifyConfigurationAndGenerateCertificates()
    {

        $result = $this->client->selfTest(
            authorization: $this->authorizations->first(),
        );

        if (!$result) {
            dump("Could not verify ownership via " . Client::VALIDATION_HTTP);
        } else {

            $isValidatedOnline = $this->validateOnline($this->authorizations->first(), Client::VALIDATION_HTTP);

            if ($isValidatedOnline) {
                $this->certificates = $this->getCertificates();
                dump($this->certificates);
            } else {
                dump("Let's Encrypt was unable to verify your domain ownership");
            }
        }
    }

    /**
     * @throws Exception
     */
    private function validateOnline(Authorization $authorization, string $type = Client::VALIDATION_HTTP): bool|string
    {
        if ($type == Client::VALIDATION_HTTP) {
            return $this->client->validate($authorization->getHttpChallenge());
        } elseif ($type == Client::VALIDATION_DNS) {
            return $this->client->validate($authorization->getDnsChallenge());
        }
        return false;
    }

    /**
     * @throws Exception
     */
    private function getCertificates(): array
    {
        $isReady = $this->client->isReady($this->order);

        if ($isReady) {
            try {
                $certificate = $this->client->getCertificate($this->order);
                $certificates = [
                    "certificate.cert" => $certificate->getCertificate(),
                    "private.key" => $certificate->getPrivateKey(),
                    "domain_certificate" => $certificate->getCertificate(false),
                    "intermediate_certificate" => $certificate->getIntermediate(),
                ];

                $sslStorePath = "registered-user-ssl/{$this->form->domain}";

                File::ensureDirectoryExists($sslStorePath);

                foreach ($certificates as $name => $content) {
                    $path = join_paths($sslStorePath, $name);
                    File::put($path, $content);
                }

                return $certificates;
            } catch (Exception $exception) {
                //during staging mode, letsencrypt doesn't return certificate,
                //let's fake it
                if ($this->mode === Modes::Staging) {
                    //render fake
                    dump("In staging mode, certificate won't be generated");
                } else {
                    dump($exception);
                }
            }
        }

        throw new Exception("Order is not ready");
    }

    public function render(): Application|Factory|View|\Illuminate\View\View
    {
        return view('livewire.domain-certificate-generator');
    }
}
