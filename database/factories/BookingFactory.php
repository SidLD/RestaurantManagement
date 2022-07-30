<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $users = User::where('role_id', 1)->get();
        $users_id = [];
        foreach($users as $user){
            array_push($users_id, $user->id);
        }
        //Random userid where user is not admin or employee
        return [
            'user_id' => $users_id[rand(0, count($users_id)-1)],
            'status' => 'pending',
            'date' => Carbon::now()->format('Y-m-d'),
            'total' => 0,
            'created_at' => Carbon::now()
        ];
    }
}
