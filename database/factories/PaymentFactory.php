<?php

use Faker\Generator as Faker;

$factory->define(\App\Payment::class, function (Faker $faker) {
    $purse_from = $faker->numberBetween(1, 1000);
    $purse_to = $faker->numberBetween(1, 1000);
    if ($purse_from === $purse_to) {
        $purse_to = $purse_to > 1 ? $purse_to - 1 : $purse_to + 1;
    }

    return [
        'purse_from' => $purse_from,
        'purse_to'   => $purse_to,
        'amount_to'  => $faker->numberBetween(1, 100),
    ];
});
