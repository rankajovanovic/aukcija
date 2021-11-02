<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
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
            'price'=> $this->faker->numberBetween($min = 1000, $max = 90000),
            'payment'=> $this->faker->randomElement($array = array ('Card','Cash')) ,
            'delivery'=> $this->faker->randomElement($array = array ('Regular','Fast')),
            'image'=> $this->faker->image('public/storage/images',640,480, null, false)
        ];
    }
}
