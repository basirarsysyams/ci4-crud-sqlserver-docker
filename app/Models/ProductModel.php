<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['category_id', 'name', 'price'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    public function getWithCategory()
    {
        return $this->select('products.*, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->orderBy('products.id', 'DESC');
    }

    public function getDataTables($request)
    {
        $builder = $this->db->table('products')
            ->select('products.id, products.name, products.price, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id', 'left');

        // 1. Total semua data
        $totalRecords = $this->db->table('products')->countAllResults();

        // 2. Search / Filter (PERBAIKAN: Gunakan getPost)
        $keyword = $request->getPost('search')['value'] ?? '';
        if (!empty($keyword)) {
            $builder->groupStart()
                ->like('products.name', $keyword)
                ->orLike('categories.name', $keyword)
                ->groupEnd();
        }

        // 3. Total data setelah difilter
        $filteredRecords = $builder->countAllResults(false);

        // 4. Order / Sorting (PERBAIKAN: Gunakan getPost)
        $order = $request->getPost('order')[0] ?? null;
        $columns = ['products.id', 'products.name', 'categories.name', 'products.price'];
        if ($order) {
            $orderColumn = $columns[$order['column']] ?? 'products.id';
            $orderDir    = $order['dir'] ?? 'desc';
            $builder->orderBy($orderColumn, $orderDir);
        } else {
            $builder->orderBy('products.id', 'DESC');
        }

        // 5. Limit / Pagination (PERBAIKAN: Gunakan getPost)
        $length = (int) $request->getPost('length') ?: 10;
        $start  = (int) $request->getPost('start') ?: 0;

        $data = $builder->get($length, $start)->getResultArray();

        // 6. Return JSON (PERBAIKAN: Gunakan getPost untuk draw)
        return [
            'draw'            => (int) $request->getPost('draw'),
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data'            => $data,
        ];
    }
}
