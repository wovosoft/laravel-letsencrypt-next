<?php

namespace App\Livewire\Forms;

use Illuminate\Validation\Rule;
use Livewire\Form;
use Wovosoft\LaravelLetsencryptCore\Client;

class GuestSslGenerator extends Form
{
    public ?string $domain = null;
    public ?string $email = null;
    public ?string $type = Client::VALIDATION_HTTP;

    public function rules(): array
    {
        return [
            "domain" => "required|string",
            "email" => "required|email",
            "type" => [
                "required",
                Rule::in([
                    Client::VALIDATION_HTTP,
                    Client::VALIDATION_DNS
                ])
            ],
        ];
    }
}
