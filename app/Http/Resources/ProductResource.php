<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'kategori' => $this->kategori,
            'kategori_label' => Product::$kategoriOptions[$this->kategori] ?? $this->kategori,
            'isi_per_pack' => $this->isi_per_pack,
            'satuan_pack' => $this->satuan_pack,
            'satuan_pack_label' => Product::$satuanPackOptions[$this->satuan_pack] ?? $this->satuan_pack,
            'stok' => $this->when($request->has('include_stock'), function () {
                $masuk = $this->purchaseItems->sum('jumlah_pack');
                $keluar = $this->stockOuts->sum('jumlah_pack');
                return max(0, $masuk - $keluar);
            }),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
