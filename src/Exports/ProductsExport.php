<?php

namespace Takshak\Ashop\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Takshak\Ashop\Models\Shop\Product;

class ProductsExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithStyles
{
    use Exportable;

    public $metaNames;
    public function query()
    {
        return Product::query()
            ->with('brand')
            ->with('categories')
            ->with('metas')
            ->with('user')
            ->when(request()->get('search'), function ($query) {
                $query->where('tags', 'like', '%' . request()->get('search') . '%');
            })
            ->when(request()->get('category'), function ($query) {
                $query->whereHas('categories', function ($query) {
                    $query->where('categories.slug', request()->get('category'));
                    $query->orWhere('categories.id', request()->get('category'));
                    $query->orWhere('categories.name', request()->get('category'));
                    $query->orWhere('categories.display_name', request()->get('category'));
                });
            })
            ->when(request()->get('brand_id'), function ($query) {
                $query->where('brand_id', request()->get('brand_id'));
            })
            ->when(request()->get('min-net_price'), function ($query) {
                $query->where('net_price', '>=', request()->get('min-net_price'));
            })
            ->when(request()->get('max-net_price'), function ($query) {
                $query->where('net_price', '<=', request()->get('max-net_price'));
            })
            ->when(request()->get('min-sell_price'), function ($query) {
                $query->where('sell_price', '>=', request()->get('min-sell_price'));
            })
            ->when(request()->get('max-sell_price'), function ($query) {
                $query->where('sell_price', '<=', request()->get('max-sell_price'));
            })
            ->when(request()->get('min-stock'), function ($query) {
                $query->where('stock', '>=', request()->get('min-stock'));
            })
            ->when(request()->get('max-stock'), function ($query) {
                $query->where('stock', '<=', request()->get('max-stock'));
            })
            ->when(request()->get('status') != null, function ($query) {
                $query->where('status', request()->get('status'));
            })
            ->when(request()->get('featured') != null, function ($query) {
                $query->where('featured', request()->get('featured'));
            })
            ->when(request()->get('user_id') != null, function ($query) {
                $query->where('user_id', request()->get('user_id'));
            });
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'A'    => [
                'font' => [
                    'bold'  => true,
                    'color' =>  array('rgb' => 'ed3a3a')
                ]
            ],

            1    => [
                'font' => [
                    'bold'  => true,
                    'size'  =>  11,
                    'color' =>  array('rgb' => 'FFFFFF')
                ],
                'fill' => [
                    'fillType'  => Fill::FILL_SOLID,
                    'color' =>  array('rgb' => '444444')
                ]
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'subtitle',
            'Image',
            'Net Price',
            'Sell Price',
            'Deal Price',
            'Deal Expiry',
            'Stock',
            'Brand',
            'Featured',
            'Status',
            'Info',
            'search_tags',
            'Categories',
            'user_id',
            'created_at'
        ];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            $product->subtitle,
            $product->image_lg(),
            $product->net_price,
            $product->sell_price,
            $product->deal_price,
            $product->deal_expiry,
            $product->stock,
            $product->brand ? $product->brand->name : '',
            $product->featured ? 'yes' : '',
            $product->status ? 'yes' : '',
            $product->info,
            $product->search_tags,
            $product->categories->pluck('id')->implode('|'),
            $product->user->name,
            $product->created_at,
        ];
    }
}
