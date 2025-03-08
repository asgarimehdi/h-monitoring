<?php

namespace Database\Seeders;

use App\Models\Person;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Person::create([
            'n_code' => '4411015056', // یا هر کد ملی مورد نظر
            'f_name'=> 'مهدی' ,
            'l_name'=> 'عسگری',
            't_id'=> '1',
            'e_id'=> '1',
            's_id'=> '1',
            'r_id'=> '1',
            'u_id'=> '1',
        ]);
    }
}
