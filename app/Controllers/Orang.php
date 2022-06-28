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

        $keyword = $this->request->getVar('keyword');
        if ($keyword) {
            $orang = $this->orangModel->search($keyword);
        } else {
            $orang = $this->orangModel;
        }
        $data = [
            'title' => 'Daftar Orang | Web Programming',
            // 'orang' => $this->orangModel->findAll()
            'orang' => $orang->paginate($jmlHalaman, 'orang'),
            'pager' => $this->orangModel->pager,
            'currentPage' => $currentPage,
            'jmlHalaman'    => $jmlHalaman
        ];

        return view('orang/index', $data);
    }
}
