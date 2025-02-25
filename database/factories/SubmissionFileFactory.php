<?php

namespace Database\Factories;

use App\Models\Submission;
use App\Models\SubmissionFile;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubmissionFileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SubmissionFile::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'submission_id' => Submission::factory(),
            'path' => 'files/' . $this->faker->uuid() . '.pdf',
            'original_filename' => $this->faker->word() . '.pdf',
            'mime_type' => 'application/pdf',
            'size' => $this->faker->numberBetween(10000, 1000000),
        ];
    }
}
