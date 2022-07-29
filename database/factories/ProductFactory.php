<?php

namespace Database\Factories;

use Carbon\Carbon;
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
    public function definition()
    {
        $food_list = [
            'Dairy Queen Chicken Strip Basket',
            'Panera Soup',
            "Hardee's Original Thickburger",
            "McDonald's Filet-O-Fish Sandwich",
            "Little Caesars Crazy Bread",
            "KFC Extra Crispy Chicken",
            "Culver's Wisconsin Cheese Curds",
            "Five Guys Fries",
            "Wendy's Baked Potato",
            "Long John Silver's Two-Piece Fish",
            "Burger King Chicken Fries",
            "KFC Popcorn Chicken Nuggets",
            "Dunkin' Munchkins",
            "Subway Cookies",
            "Chick-fil-A Iced Tea"
        ];

        return [
            'name' => $this->faker->randomElement($food_list),
            'price' => $this->faker->numerify('##.##'),
            'image_id' => 0, //Will get modified in Seeder
            'created_at' => Carbon::now()->format('Y-m-d'),
            'display' => true
        ];
    }
}
