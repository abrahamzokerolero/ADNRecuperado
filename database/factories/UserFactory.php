<?php

use Faker\Generator as Faker;
// use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'username' => $faker->unique()->userName,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'avatar'=> $faker->imageUrl(300,300, 'people'),
    ];
});

/*
$factory->define(App\Messages::class, function (Faker $faker) {
	// $date = Carbon::now();
	// $date = $date->format('M j Y h:i:s');

    return [
    	'created_at' => $faker->dateTimeThisDecade(),
        'updated_at' => $faker->dateTimeThisDecade(),
        'content' => $faker->realText(random_int(20, 160)),
        'image' => $faker->imageUrl(600,338),
    ];
});

*/

$factory->define(App\Categoria::class, function (Faker $faker) {
    // $date = Carbon::now();
    // $date = $date->format('M j Y h:i:s');

    return [
        'created_at' => $faker->dateTimeThisDecade(),
        'updated_at' => $faker->dateTimeThisDecade(),
        'nombre' => $faker->unique()->company(), 
    ];
});

$factory->define(App\Etiqueta::class, function (Faker $faker) {
    // $date = Carbon::now();
    // $date = $date->format('M j Y h:i:s');

    return [
        'created_at' => $faker->dateTimeThisDecade(),
        'updated_at' => $faker->dateTimeThisDecade(),
        'nombre' => $faker->unique()->secondaryAddress,
        'categoria_id' => $faker->randomDigit,
    ];
});

$factory->define(App\ImportacionFrecuencia::class, function (Faker $faker) {
    // $date = Carbon::now();
    // $date = $date->format('M j Y h:i:s');

    return [
        'created_at' => $faker->dateTimeThisDecade(),
        'updated_at' => $faker->dateTimeThisDecade(),
        'nombre' => $faker->domainName,
        'id_usuario' => $faker->randomDigit,
    ];
});

$factory->define(App\Frecuencia::class, function (Faker $faker) {
    // $date = Carbon::now();
    // $date = $date->format('M j Y h:i:s');

    return [
        'created_at' => $faker->dateTimeThisDecade(),
        'updated_at' => $faker->dateTimeThisDecade(),
        'id_importacion' => $faker->randomDigit,
        'marcador' => $faker->word,
        'alelo' => $faker->randomDigit,
        'frecuencia' => $faker->randomFloat($nbMaxDecimals = 4, $min = 0, $max = 1),
    ];
});

