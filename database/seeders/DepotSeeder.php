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
            'phone_number' => '+6289696454545',
            'location' => '-0.9265289528497486, 100.43368287661342',
            'address' => 'Jalan jalan heya heyaaa',
            'price' => 5000,
            'is_open' => true
        ]);
    }
}
