<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CategoryModel;

class ProductController extends BaseController
{
    protected $productModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->productModel  = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }

    /**
     * Halaman utama dengan DataTables
     */
    public function index()
    {
        $data = [
            'title'      => 'Daftar Produk',
            'categories' => $this->categoryModel->orderBy('name', 'ASC')->findAll(),
        ];
        return view('products/index', $data);
    }

    /**
     * ENDPOINT AJAX: Mengambil data untuk DataTables (server-side)
     * Method: POST
     */
    public function ajaxList()
    {
        // Ambil data menggunakan GET
        $result = $this->productModel->getDataTables($this->request);

        // Format harga untuk tampilan
        foreach ($result['data'] as &$row) {
            $row['price_formatted'] = 'Rp ' . number_format($row['price'], 0, ',', '.');
        }

        return $this->response->setJSON($result);
    }
    /**
     * ENDPOINT AJAX: Mendapatkan satu produk untuk edit (JSON)
     */
    public function ajaxGet($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $product = $this->productModel->getWithCategory()->find($id);

        if (!$product) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Produk tidak ditemukan']);
        }

        return $this->response->setJSON(['status' => 'success', 'data' => $product]);
    }

    /**
     * ENDPOINT AJAX: Simpan produk baru
     */
    public function ajaxStore()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $rules = [
            'name' => [
                'rules'  => 'required|min_length[3]',
                'errors' => [
                    'required'   => 'Nama produk wajib diisi',
                    'min_length' => 'Nama produk minimal 3 karakter',
                ]
            ],
            'price' => [
                'rules'  => 'required|numeric|greater_than[0]',
                'errors' => [
                    'required'     => 'Harga wajib diisi',
                    'numeric'      => 'Harga harus berupa angka',
                    'greater_than' => 'Harga harus lebih dari 0',
                ]
            ],
            'category_id' => [
                'rules'  => 'required|is_not_unique[categories.id]',
                'errors' => [
                    'required'       => 'Kategori wajib dipilih',
                    'is_not_unique'  => 'Kategori tidak valid',
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Validasi gagal',
                'errors'  => $this->validator->getErrors()
            ]);
        }

        $this->productModel->insert([
            'name'        => $this->request->getPost('name'),
            'price'       => $this->request->getPost('price'),
            'category_id' => $this->request->getPost('category_id'),
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Produk berhasil ditambahkan'
        ]);
    }

    /**
     * ENDPOINT AJAX: Update produk
     */
    public function ajaxUpdate($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $product = $this->productModel->find($id);
        if (!$product) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Produk tidak ditemukan']);
        }

        $rules = [
            'name' => [
                'rules'  => 'required|min_length[3]',
                'errors' => [
                    'required'   => 'Nama produk wajib diisi',
                    'min_length' => 'Nama produk minimal 3 karakter',
                ]
            ],
            'price' => [
                'rules'  => 'required|numeric|greater_than[0]',
                'errors' => [
                    'required'     => 'Harga wajib diisi',
                    'numeric'      => 'Harga harus berupa angka',
                    'greater_than' => 'Harga harus lebih dari 0',
                ]
            ],
            'category_id' => [
                'rules'  => 'required|is_not_unique[categories.id]',
                'errors' => [
                    'required'       => 'Kategori wajib dipilih',
                    'is_not_unique'  => 'Kategori tidak valid',
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Validasi gagal',
                'errors'  => $this->validator->getErrors()
            ]);
        }

        $this->productModel->update($id, [
            'name'        => $this->request->getPost('name'),
            'price'       => $this->request->getPost('price'),
            'category_id' => $this->request->getPost('category_id'),
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Produk berhasil diperbarui'
        ]);
    }

    /**
     * ENDPOINT AJAX: Hapus produk
     */
    public function ajaxDelete($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $product = $this->productModel->find($id);
        if (!$product) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Produk tidak ditemukan']);
        }

        $this->productModel->delete($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Produk berhasil dihapus'
        ]);
    }
}
