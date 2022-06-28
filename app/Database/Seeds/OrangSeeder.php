<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class OrangSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');
        // $data = [
        //     [
        //         'nama' => 'Aul',
        //         'alamat'    => 'Bekasi',
        //         'created_at' => Time::now(),
        //         'updated_at' => Time::now()
        //     ],
        //     [
        //         'nama' => 'Rahman',
        //         'alamat'    => 'Jakarta',
        //         'created_at' => Time::now(),
        //         'updated_at' => Time::now()
        //     ],
        //     [
        //         'nama' => 'Doddy Ferdiansyah',
        //         'alamat'    => 'Bandung',
        //         'created_at' => Time::now(),
        //         'updated_at' => Time::now()
        //     ],
        // ];

        for ($i = 0; $i < 1000; $i++) {
            $data = [
                'nama' => $faker->name,
                'alamat'    => $faker->address,
                'created_at' => Time::createFromTimestamp($faker->unixTime()),
                'updated_at' => Time::createFromTimestamp($faker->unixTime())
            ];
            $this->db->table('orang')->insert($data);
        }

        // Simple Queries
        // $this->db->query('INSERT INTO orang (nama, alamat, created_at, updated_at) VALUES(:nama:, :alamat:, :created_at:, :updated_at:)', $data);

        // Using Query Builder

        // $this->db->table('orang')->insertBatch($data);
    }
}
