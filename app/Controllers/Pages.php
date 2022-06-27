<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Pages extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Home | Web Programming'
        ];
        return view('pages/home', $data);
    }

    public function about()
    {
        $data = [
            'title' => 'About Me | Web Programming'
        ];
        return view('pages/about', $data);
    }

    public function contact()
    {
        $data = [
            'title' => 'Contact Us | Web Programming',
            'alamat' => [
                [
                    'tipe'  => 'Rumah',
                    'alamat' => 'Jl. Abc No.123',
                    'kota'      => 'Bandung'
                ],
                [
                    'tipe'  => 'Kantor',
                    'alamat' => 'Jl. Abc No.123',
                    'kota'      => 'Jakarta'
                ]
            ]
        ];
        return view('pages/contact', $data);
    }
}
