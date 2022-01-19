<?php


namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NichesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('niches')->truncate();

        DB::table('niches')->insert(
            [
                [
                    'name' => 'Comédia',
                ],
                [
                    'name' => 'Esporte',
                ],
                [
                    'name' => 'Saúde',
                ],
                [
                    'name' => 'Beleza',
                ],
            ]
        );
    }
}
