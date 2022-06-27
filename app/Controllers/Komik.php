<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KomikModel;

class Komik extends BaseController
{

    protected $komikModel;

    public function __construct()
    {
        $this->komikModel = new KomikModel();
    }

    public function index()
    {
        // $komik =  $this->komikModel->findAll();
        $data = [
            'title' => 'Daftar Komik | Web Programming',
            'komik' => $this->komikModel->getKomik()
        ];

        return view('komik/index', $data);
    }

    public function detail($slug)
    {
        $komik = $this->komikModel->getKomik($slug);
        $data = [
            'title' => 'detail Komik | Web Programming',
            'komik' => $komik
        ];


        if (empty($komik)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Judul komik {$slug} tidak ditemukan");
        }

        return view('komik/detail', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Komik | Web Programming',
            'validation'    => \Config\Services::validation()
        ];
        return view('komik/create', $data);
    }

    public function save()
    {
        // dd($this->request->getVar());
        if (!$this->validate([
            'judul'     => [
                'rules'     => 'required|is_unique[komik.judul]',
                'errors'    => [
                    'required'  => '{field} komik harus diisi!',
                    'is_unique' => '{field} komik sudah tersedia!'
                ]
            ],
            'penulis'   => 'required',
            'sampul'    => [
                'rules'     => 'max_size[sampul,1024]|ext_in[sampul,png,jpg,jpeg]|mime_in[sampul,image/png,image/jpg,image/jpeg]',
                'errors'    => [
                    'max_size'      => 'Ukuran gambar terlalu besar!'
                ]
            ]
        ])) {
            // $validation = \Config\Services::validation();
            // return redirect()->to('/komik/create')->withInput()->with('validation', $validation);
            return redirect()->to('/komik/create')->withInput();
        }

        // ambil gambar
        $fileSampul = $this->request->getFile('sampul');
        // cek gambar yg di upload
        if ($fileSampul->getError() == 4) {
            $namaSampul = 'default.jpg';
        } else {
            // generate nama sampul random
            $namaSampul = $fileSampul->getRandomName();
            // pindahkan/upload file ke folder img
            $fileSampul->move('img', $namaSampul);
            // ambil nama file
            // $namaSampul = $fileSampul->getName();
        }


        $slug = url_title($this->request->getVar('judul'), '-', true);
        $this->komikModel->save([
            'judul'     => $this->request->getVar('judul'),
            'slug'      => $slug,
            'penulis'     => $this->request->getVar('penulis'),
            'penerbit'     => $this->request->getVar('penerbit'),
            'sampul'     => $namaSampul
        ]);

        session()->setFlashdata('pesan', 'Data berhasil ditambahkan');

        return redirect()->to('/komik');
    }

    public function delete($id)
    {
        // cari gambar berdasarkan id
        $komik = $this->komikModel->find($id);
        // cek jika file gambarnya default.jpg
        if ($komik['sampul'] != 'default.jpg') {
            // hapus gambar
            @unlink("img/{$komik['sampul']}");
        }

        $this->komikModel->delete($id);
        session()->setFlashdata('pesan', 'Data berhasil dihapus');

        return redirect()->to('/komik');
    }

    public function edit($slug)
    {
        $data = [
            'title' => 'Edit Komik | Web Programming',
            'validation'    => \Config\Services::validation(),
            'komik'     => $this->komikModel->getKomik($slug)
        ];
        return view('komik/edit', $data);
    }

    public function update($id)
    {
        // cek judul
        $komikLama = $this->komikModel->getKomik($this->request->getVar('slug'));

        if ($komikLama['judul'] == $this->request->getVar('judul')) {
            $rule_judul = 'required';
        } else {
            $rule_judul = 'required|is_unique[komik.judul]';
        }
        // end cek judul
        if (!$this->validate([
            'judul'     => [
                'rules'     => $rule_judul,
                'errors'    => [
                    'required'  => '{field} komik harus diisi!',
                    'is_unique' => '{field} komik sudah tersedia!'
                ]
            ],
            'penulis'   => 'required',
            'sampul'    => [
                'rules'     => 'max_size[sampul,1024]|ext_in[sampul,png,jpg,jpeg]|mime_in[sampul,image/png,image/jpg,image/jpeg]',
                'errors'    => [
                    'max_size'      => 'Ukuran gambar terlalu besar!'
                ]
            ]
        ])) {

            return redirect()->to("/komik/edit/{$this->request->getVar('slug')}")->withInput();
        }

        $fileSampul = $this->request->getFile('sampul');

        // cek gambar, apakah gambar yg diupload
        if ($fileSampul->getError() == 4) {
            $namaSampul = $this->request->getVar('sampulLama');
        } else {
            // generate nama sampul random
            $namaSampul = $fileSampul->getRandomName();
            // pindahkan/upload file ke folder img
            $fileSampul->move('img', $namaSampul);
            // hapus file lama
            unlink("img/{$this->request->getVar('sampulLama')}");
        }

        $slug = url_title($this->request->getVar('judul'), '-', true);
        $this->komikModel->save([
            'id'        => $id,
            'judul'     => $this->request->getVar('judul'),
            'slug'      => $slug,
            'penulis'     => $this->request->getVar('penulis'),
            'penerbit'     => $this->request->getVar('penerbit'),
            'sampul'     => $namaSampul
        ]);

        session()->setFlashdata('pesan', 'Data berhasil diupdate');

        return redirect()->to('/komik');
    }
}
