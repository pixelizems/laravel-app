<?php

namespace Database\Factories;

use App\Models\Submission;
use App\Models\SubmissionImage;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubmissionImageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SubmissionImage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'submission_id' => Submission::factory(),
            'path' => 'images/' . $this->faker->uuid() . '.jpg',
            'original_filename' => $this->faker->word() . '.jpg',
            'mime_type' => 'image/jpeg',
            'size' => $this->faker->numberBetween(50000, 5000000),
        ];
    }
}
