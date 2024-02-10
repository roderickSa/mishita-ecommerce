<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsByDayExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $today = Carbon::now()->format('Y-m-d');

        return DB::table('products')
            ->select('products.id', 'title', 'products.slug', 'published', 'description', 'price', 'stock', 'categories.name as category')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->whereDate('products.created_at', '=', $today)
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Title',
            'Slug',
            'Published',
            'Description',
            'Price',
            'Stock',
            'Category',
        ];
    }
}
