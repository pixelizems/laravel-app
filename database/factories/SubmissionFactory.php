<?php

namespace Database\Factories;

use App\Models\Submission;
use Illuminate\Database\Eloquent\Factories\Factory;


class SubmissionFactory extends Factory
{
    protected $model = Submission::class;

    public function definition()
    {
        return [
            'name' => $this->faker->text(10),
            'email' => $this->faker->safeEmail,
            'phone' => $this->faker->numerify('+1##########'),
            'message' => $this->faker->text(100),
            'street' => $this->faker->streetAddress,
            'state' => $this->faker->state,
            'zip' => $this->faker->postcode,
            'country' => $this->faker->country,
        ];
    }
}
