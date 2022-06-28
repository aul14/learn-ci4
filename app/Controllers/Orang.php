<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OrangModel;

class Orang extends BaseController
{
    protected $orangModel;

    public function __construct()
    {
        $this->orangModel = new OrangModel();
    }

    public function index()
    {
        $currentPage = $this->request->getVar('page_orang') ? $this->request->getVar('page_orang') : 1;
        $jmlHalaman = 10;
        $data = [
            'title' => 'Daftar Orang | Web Programming',
            // 'orang' => $this->orangModel->findAll()
            'orang' => $this->orangModel->paginate($jmlHalaman, 'orang'),
            'pager' => $this->orangModel->pager,
            'currentPage' => $currentPage,
            'jmlHalaman'    => $jmlHalaman
        ];

        return view('orang/index', $data);
    }
}
