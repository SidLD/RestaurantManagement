<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $roles = [1, 2, 3];
        return [
            'first_name' => $this->faker->firstName(),
                'last_name' => $this->faker->lastName(),
                'contact' => $this->faker->numerify('####-###-####'),
                'email' => $this->faker->safeEmail(),
                'email_verified_at' => now(),
                'role_id' => $roles[rand(0,2)],
                'password' => Hash::make('mypassword'),
                'remember_token' => Str::random(10),
                'created_at' => Carbon::now()->format('Y-m-d')
        ];
    }
}
