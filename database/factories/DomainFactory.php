<?php

namespace Database\Factories;

use App\Models\Domain;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Domain>
 */
class DomainFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "domain" => $this->faker->domainName(),
            "email" => $this->faker->safeEmail(),
            "user_id" => $this->faker->randomElement(User::query()->pluck('id')),
            "verification_file_upload_url" => $this->faker->url(),
            "verification_file_upload_params" => json_encode([
                "username" => $this->faker->userName(),
                "password" => $this->faker->password(),
            ])
        ];
    }
}
