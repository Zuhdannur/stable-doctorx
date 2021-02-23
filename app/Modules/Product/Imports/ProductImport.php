<?php
  
namespace App\Modules\Product\Imports;
  
use App\Modules\Product\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
  
class ProductImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    private $input;

    public $count = 0;

    public function __construct($param)
    {
        $this->input = $param;
    }

    //LIMIT CHUNKSIZE
    public function chunkSize(): int
    {
        return 1000; //ANGKA TERSEBUT PERTANDA JUMLAH BARIS YANG AKAN DIEKSEKUSI
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $data)
    {
        // die(print_r($data));
        if($data){
            foreach ($data as $key => $row) {
                $code = $row['kode'];
                $name = $row['nama_produk'];
                $catId = $this->input['category_id'];
                $price = isset($row['price']) ? $row['price'] : 70000;

                $dataInput = array(
                    'code' => $code,
                    'name' => strtoupper($name),
                    'category_id' => $catId,
                    'price' => $price,
                    'quantity' => 79,
                    'is_active' => 1,
                );
                $exec = Product::updateOrCreate(['code' => $code, 'name' => $name, 'category_id' => $catId], $dataInput);
            }
        }
        
        
        return $this->count;
    }
}