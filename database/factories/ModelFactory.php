<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password = '123123';

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'phone' => '9999999999',
        'slack_webhook_url' => 'https://hooks.slack.com/services/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',
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});
