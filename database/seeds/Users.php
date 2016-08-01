<?php

class Users extends Seeder
{
    public function init()
    {
        $faker = $this->faker;

        $this->define('users', function () use ($faker) {

            $status = ['active', 'inactive'];

            return [
                'status' => $status[array_rand($status)],
                'name'   => $faker->name,
            ];
        }, $truncate = true);
    }

    public function run()
    {
        $this->seed(function ($counter) {
            $this->factory('users')->create();
        });

        return;
    }
}
