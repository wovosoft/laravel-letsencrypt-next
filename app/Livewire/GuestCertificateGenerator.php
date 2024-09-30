<?php

namespace App\Livewire;

use App\Livewire\Forms\GuestSslGenerator;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Wovosoft\LaravelLetsencryptCore\Client;
use Wovosoft\LaravelLetsencryptCore\Data\Authorization;
use Wovosoft\LaravelLetsencryptCore\Data\Order;
use Wovosoft\LaravelLetsencryptCore\Enums\Modes;
use function Illuminate\Filesystem\join_paths;

class GuestCertificateGenerator extends Component
{
    public GuestSslGenerator $form;
    public bool $showFirstStep = true;
    public bool $showSecondStep = false;
    public bool $showThirdStep = false;
    public ?int $orderId = null;
    public array $certificates = [];

    private Client $client;
    private Order $order;
    private $mode = Modes::Live;

    private ?string $httpVerificationFile = null;

    /**
     * @var Collection<int,Authorization>
     */
    private Collection $authorizations;

    /**
     * @throws Exception
     */
    public function handleFirstStep(): void
    {
        $this->form->validate();
        $this
            ->createClient($this->form->email)
            ->createOrderAndSetAuthorizations($this->form->domain)
            ->displayDomainAuthorizationChallenges();

        $this->showStep(2);
    }

    public function showStep(int $step): void
    {
        $this->showFirstStep = 1 === $step;
        $this->showSecondStep = 2 === $step;
        $this->showThirdStep = 3 === $step;
    }

    /**
     * @throws \Exception
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
     * @throws \Exception
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

    public function downloadHttpAuthorizationFile(string $path): BinaryFileResponse
    {
        $fileName = basename($path); // Extract the file name from the path
        return response()->download(Storage::path($path), $fileName);
    }

    private function displayDomainAuthorizationChallenges(): static
    {
        foreach ($this->authorizations as $authorization) {
            if ($this->form->type === Client::VALIDATION_HTTP) {
                $file = $authorization->getFile();

                $storeFilePath = "guests-ssl-verification/{$file->getFilename()}";

                Storage::put($storeFilePath, $file->getContents());

                $this->httpVerificationFile = $storeFilePath;


//                $data = [
//                    "file_path" => $storeFilePath,  //we should work on the relative path //Storage::path($storeFilePath),
//                    "file_name" => $file->getFilename(),
//                    "file_contents" => $file->getContents()
//                ];
//
//                dump($data);
            } elseif ($txtRecord = $authorization->getTxtRecord()) {
                $data = [
                    "domain" => $authorization->getDomain(),
                    "txt_record_name" => $txtRecord?->getName(),
                    "txt_record_value" => $txtRecord?->getValue()
                ];

                dump($data);
            }
        }

        return $this;
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
     * @throws \Exception
     */
    public function verifyConfigurationAndGenerateCertificates()
    {
        //retrieve the client
        $this->createClient($this->form->email);
        $this->order = $this->client->getOrder($this->orderId);

        //retrieve authorizations with challenges
        $this->authorizations = $this->client->authorize($this->order);

        $result = $this->client->selfTest(
            authorization: $this->authorizations->first(),
        );

        if (!$result) {
            dump("Could not verify ownership via " . Client::VALIDATION_HTTP);
        } else {

            $isValidatedOnline = $this->validateOnline($this->authorizations->first(), Client::VALIDATION_HTTP);

            if ($isValidatedOnline) {
                $this->certificates = $this->getCertificates();
                $this->showStep(3);
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

                $sslStorePath = "guests-ssl/{$this->form->domain}";

                File::ensureDirectoryExists($sslStorePath);

                foreach ($certificates as $name => $content) {
                    $path = join_paths($sslStorePath, $name);
                    File::put($path, $content);
                }

                return $certificates;
            } catch (\Exception $exception) {
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
        return view('livewire.guest-certificate-generator', [
            "verificationMethods" => [
                "HTTP" => Client::VALIDATION_HTTP,
                "DNS" => Client::VALIDATION_DNS
            ]
        ]);
    }
}
