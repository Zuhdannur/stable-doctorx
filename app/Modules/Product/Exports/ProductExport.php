<?php
  
namespace App\Modules\Product\Exports;
  
use App\Modules\Product\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ProductExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Product::all();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            '#',
            'Name',
            'Price',
            'Created At',
            'Updated At',
        ];
    }
}