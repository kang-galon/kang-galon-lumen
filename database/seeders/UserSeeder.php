<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Client
        DB::table('users')->insert([
            'phone_number' => '+6289696121212',
            'name' => 'Client',
            'uid' => Str::random(28),
            'status' => 2,
            'created_at' => Carbon::now(),
        ]);

        // Depot
        DB::table('users')->insert([
            'phone_number' => '+6289696454545',
            'name' => 'Depot',
            'uid' => Str::random(28),
            'status' => 1,
            'created_at' => Carbon::now(),
        ]);
    }
}
