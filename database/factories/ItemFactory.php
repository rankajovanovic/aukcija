<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Carbon\Carbon;

class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::all()->random()->id,
            'name' => $this->faker->word(),
            'description'=> $this->faker->text($maxNbChars = 200),
            'price'=> $this->faker->numberBetween($min = 200, $max = 20000),
            'payment'=> $this->faker->randomElement($array = array ('Card','Cash')) ,
            'delivery'=> $this->faker->randomElement($array = array ('Regular','Fast')),
            'image'=> 'https://placeimg.com/500/500/tech?' . rand(1, 100),
            'end_time' => Carbon::now()->addDays(10),
        ];
    }
}
