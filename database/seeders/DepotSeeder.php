<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('depots')->insert([
            'phone_number' => '+6289696111111',
            'location' => '-0.9266900205415834, 100.43332932181613',
            'address' => 'Jalan Mohammad Hatta, Padang, Sumatera Barat, 25162
            Pauh Padang Indonesia',
            'price' => 5000,
            'is_open' => true
        ]);

        DB::table('depots')->insert([
            'phone_number' => '+6289696222222',
            'location' => '-0.9286035908995458, 100.43381415652418',
            'address' => 'Jalan Malintang, Padang, Sumatera Barat, 25162
            Pauh Padang Indonesia',
            'price' => 5000,
            'is_open' => true
        ]);

        DB::table('depots')->insert([
            'phone_number' => '+6289696333333',
            'location' => '-0.9263073063458704, 100.43345690989719',
            'address' => 'Jalan Mohammad Hatta, Padang, Sumatera Barat, 25162
            Pauh Padang Indonesia',
            'price' => 5000,
            'is_open' => true
        ]);

        DB::table('depots')->insert([
            'phone_number' => '+6289696444444',
            'location' => '-0.9242916775667838, 100.44590950660931',
            'address' => 'Jalan Mohammad Hatta, Padang, Sumatera Barat, 25162
            Pauh Padang Indonesia',
            'price' => 5000,
            'is_open' => true
        ]);

        DB::table('depots')->insert([
            'phone_number' => '+6289696555555',
            'location' => '-0.9249295348992033, 100.43909630308036',
            'address' => 'Jalan Mohammad Hatta, Padang, Sumatera Barat, 25162
            Pauh Padang Indonesia',
            'price' => 5000,
            'is_open' => true
        ]);
    }
}
