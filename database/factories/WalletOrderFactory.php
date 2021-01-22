<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Zhiyi\Plus\Models\WalletOrder;

class WalletOrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WalletOrder::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->name,
            'target_type' => $this->faker->name,
            'target_id' => $this->faker->numberBetween(1, 100),
            'type' => $this->faker->numberBetween(-1, 1),
            'amount' => $this->faker->numberBetween(0, 100),
        ];
    }
}
