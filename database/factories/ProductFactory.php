<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        /* $name = fake()->unique()->word(); */

        $name = $this->generateRandomString(12) . '_' . floor(microtime(true) * 1000);

        return [
            'title' => $name,
            'slug' => str($name)->slug(),
            'published' => 1,
            'description' => fake()->realText(2000),
            'price' => fake()->randomFloat(2, 2, 5),
            'stock' => rand(1, 10),
            'category_id' => Category::inRandomOrder()->first()->id,
            'created_by' => User::inRandomOrder()->first()->id,
            'updated_by' => User::inRandomOrder()->first()->id,
        ];
    }

    private function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
